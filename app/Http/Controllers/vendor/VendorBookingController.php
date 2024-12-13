<?php

namespace App\Http\Controllers\vendor;

use App\Exports\ExcelExporter;
use App\Http\Controllers\Admin\YatchBookingController;
use App\Models\Vendor\Yatch;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Vendor;
use App\Models\BookingOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\CountryModel;
use App\Models\CustomerUserDetail;
use App\Models\Facility;
use App\Models\TempTransaction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor\VendorUserDetail;
use App\Models\Categories;
use App\Models\Vendor\PackageOrdAddon;
use App\Models\Vendor\PackageOrder;
use App\Models\Vendor\PackageOrdProd;
use App\Models\Vendor\ProductCategory;
use App\Models\Vendor\VendorBooking;
use App\Models\Vendor\VendorBookingDate;
use App\Models\Vendor\VendorBookingMedia;
use App\Models\Vendor\VendorPackage;
use App\Models\Vendor\YachtOrder;
use App\Models\VendorRating;
use App\Models\YachtType;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Payments\PaymentStripe;
use Illuminate\Support\Js;
use App\Helpers\FirebaseService;
use App\Models\BookingResource;
use DateTime;
use App\Models\Setting;

class VendorBookingController extends Controller
{

    public static $maxImgsAllowed = 15;

    /**
     * Display a listing of the resource.
     * @param {"admin" | "vendor"}  $type
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type, $user_id)
    {

        // Get the request headers
        $headers = $request->headers->all();

        $reporting = $request->reporting ?? null;

        $restrictedPageRoute = route_name_admin_vendor($type, 'restricted_page');



        // If it's not a reporting page then check the permission
        if (!$reporting && !get_user_permission('vendors_booking', 'r')) {
            return redirect()->route($restrictedPageRoute);
        } else if ($reporting && !get_user_permission('reporting_vendors_booking', 'r')) {
            return redirect()->route($restrictedPageRoute);
        }



        $refrence_no = $request->refrence_no ?? null;
        $order_id = $request->order_id ?? null;
        $artist_name = $request->artist_name ?? null;
        $customer_name = $request->customer_name ?? null;
        $order_status = $request->order_status ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $export = $request->export ?? null;
        $category_id = $request->category_id ?? null;

        $page_heading = "Bookings" . ($reporting ? " Reports" : "");

        // Ger vendorId variable value from  request query
        $vendorId = request()->query('vendorid');
        $editstatus = request()->query('editstatus');

        //if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route($restrictedPageRoute);
        }


        $categories = Categories::where('active', 1)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        $artist = VendorUserDetail::select('user_id')->where('category_id', $category_id)->pluck('user_id')->toArray();


        // Get the bookings by the user i
        $query = VendorBooking::with(['user', 'dates', 'customer', 'transactions']);


        if ($user_id !== "all") {
            $query->where('user_id', $user_id);
        }

        if (!empty($artist) && count($artist) > 0) {
            $query->whereIn('user_id', $artist);
        }

        if ($refrence_no) {
            $query->whereRaw('LOWER(reference_number) like ?', ['%' . strtolower($refrence_no) . '%']);
        }


        if ($order_id) {
            $query->whereRaw('LOWER(order_id) like ?', ['%' . strtolower($order_id) . '%']);
        }


        // If artistname lowercase for search
        if ($artist_name) {
            $query->whereHas('user', function ($q) use ($artist_name) {
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($artist_name) . '%']);
            });
        }

        if ($customer_name) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($customer_name) . '%']);
            });
        }


        if ($order_status) {
            $query->where('status', $order_status);
        }

        // from date
        if ($from_date != '') {
            $query->where('created_at', '>=', gmdate('Y-m-d H:i:s', strtotime($from_date . ' 00:00:00')));
        }

        // If the to_date is not null then search the to_date
        if ($to_date != '') {
            $query->where('created_at', '<=', gmdate('Y-m-d H:i:s', strtotime($to_date . ' 23:59:59')));
        }




        // -------------- Table ordering ------------
        $disableSortingColumnsIndex = [0, 1, 3, 5];
        $tableColumnsIndexMaping = array(
            // 0-> row number,
            // 1-> actions
            "reference_number" => 'vendor_bookings.reference_number',
            //"order_id" => 'vendor_bookings.order_id', // Requested by the client to remove the order id
            "order_status" => 'vendor_bookings.status',
            "customer" => 'customer',
            "artist" => 'artist',
            "deposit_balance" => 'vendor_bookings.total_paid',
            "total_amount" => 'vendor_bookings.total_without_tax',
            "tax" => 'vendor_bookings.tax',
            "disraption_fee" => 'vendor_bookings.disraption',
            "artist_commission" => 'vendor_bookings.artist_commission',
            "new_order_commission" => 'vendor_bookings.neworer_commission',
            "gateway_fee" => 'vendor_bookings.gateway',
            "net_total" => 'vendor_bookings.total_with_tax',
            "creation_date" => 'vendor_bookings.created_at',
        );

        // ready the sorting name
        $sort_name = '';
        $sort_order = '';

        // if the request has sort_index and sort_order then set the values
        if ($request->has('sort_index') && $request->has('sort_order')) {
            $sort_name = $request->sort_index;
            $sort_order = $request->sort_order;
        }



        if ($sort_name && $sort_order) {

            // ______ If sort name is found then add the select to the query so we can order by the column ____

            switch ($sort_name) {

                case 'customer':

                    // Join related tables for sorting by customer
                    $query->leftJoin('users', 'users.id', '=', 'vendor_bookings.customer_id')
                        ->select('vendor_bookings.*', 'users.name as user_name');

                    $query->orderBy('user_name', $sort_order);
                    break;

                case 'artist':
                    // order by the total bookings
                    // using with count
                    $query->leftJoin('users', 'users.id', '=', 'vendor_bookings.user_id')
                        ->select('vendor_bookings.*', 'users.name as user_name');
                    $query->orderBy('user_name', $sort_order);
                    break;

                default:
                    // order the queries which can be directly order
                    $query->orderBy($tableColumnsIndexMaping[$sort_name], $sort_order);
                    break;
            }


            // _________________________________________________________________________________________________

        } else {
            $query->orderByRaw('COALESCE(updated_at, created_at) DESC');
        }


        // ------------------------------------------


        if ($export) {
            return $this->excelReporting($query);
        }


        //dd($query->toSql(), $query->getBindings());



        $bookings = $query->paginate(10);

        //dd($bookings->toArray());


        $viewType = "vendor";


        // Pass the data to the view
        return view('admin.veondor_booking.list', compact('page_heading', 'type', 'user_id', 'bookings', 'editstatus', 'viewType', 'categories', 'disableSortingColumnsIndex'));
    }





    public function create($type, $vendor_user_id)
    {

        $restrictedPageRoute = route_name_admin_vendor($type, 'restricted_page');

        if (!get_user_permission('vendors_booking', 'c')) {
            return redirect()->route($restrictedPageRoute);
        }


        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($vendor_user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route($restrictedPageRoute);
        }



        $page_heading = "Add Booking";
        $id = "";
        $user_id = $vendor_user_id;

        $date = "";
        $start_time = "";
        $end_time = "";
        $title = "";
        $duration = 0.5;
        $durationArray = $this->durationSelectBoxArray();

        // Generate refrerence number of 7 digits with prefix D
        $reference_number = "D" . rand(1000000, 9999999);

        $total = 0;
        $advance = 0;
        $order_id = "";

        $booking_dates = [];
        $booking = null;

        $medias = [];
        $maxImgsAllowed = VendorBookingController::$maxImgsAllowed;

        // Get all vendors list
        $vendors_query = User::select('name', 'id')->where('user_type_id', 3)->with(['vendor_details' => function ($query) {

            $query->select('user_id', 'hourly_rate', 'deposit_amount', 'username');
        }]);

        // if user_id is not all then add the where
        if ($user_id !== "all") {
            $vendors_query->where('id', $user_id);
        }


        $vendors = $vendors_query->get();


        // Convert to array
        $vendors_array = $vendors->keyBy('id')->toArray();


        // Get all customers users
        $customers = User::where('user_type_id', 2)->get();

        // Get all countries
        $countries = CountryModel::orderBy('name', 'asc')->get();

        // Get all resources
        $booking_resources = BookingResource::getAll();
        $booking_resources = $booking_resources->toArray();

        $vendor_future_bookings = $this->getVendorFutureBookingDates(null, $id);

        return view('admin.veondor_booking.create', compact('page_heading', 'type', 'user_id', 'vendor_user_id', 'id', 'date', 'start_time', 'end_time', 'title', 'reference_number', 'total', 'advance', 'order_id', 'booking_dates', 'booking', 'vendors', 'customers', 'vendors_array', 'medias', 'maxImgsAllowed', 'countries', 'booking_resources', 'vendor_future_bookings', 'duration', 'durationArray'));
    }


    public function edit($type, $vendor_user_id, $id)
    {
        if (!get_user_permission('vendors_booking', 'u')) {
            return redirect()->route('admin.restricted_page');
        }


        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($vendor_user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
          return redirect()->route('admin.restricted_page');
        }


        // Get the booking by the user id and the booking id
        $booking = VendorBooking::with(['dates', 'transactions', 'customer', 'vendor'])->where('user_id', $vendor_user_id)->where('id', $id)->first();

        $page_heading = "Add Booking";
        $id = $id;
        $user_id = $vendor_user_id;

        // $date = $booking->date;
        // $start_time = $booking->start_time;
        // $end_time = $booking->end_time;
        $title = $booking->title;
        $reference_number = $booking->reference_number;

        $total = $booking->total;
        $advance =  $booking->advance;
        $order_id = "";
        $duration = $booking->duration;
        $durationArray = $this->durationSelectBoxArray();

        $all_bookings = VendorBooking::with(['dates', 'transactions', 'customer', 'vendor'])
            ->where('user_id', $vendor_user_id)
            ->whereDate('created_at', Carbon::parse($booking->created_at)->toDateString())
            ->get();

        $booking_dates = [];
        foreach ($all_bookings as $booking) {
            if (!empty($booking->dates)) {
                $date = $booking->dates->first()->toArray();
                $date['status'] = $booking->status;
                $booking_dates[] = $date;
            }
        }
//        dd($booking_dates);

//        $booking_dates = $booking->dates->toArray();

        $medias = $booking->medias;
        $maxImgsAllowed = VendorBookingController::$maxImgsAllowed;

        // looop through the booking dates and convert the time to 12-hour format
        foreach ($booking_dates as $key => $value) {
            $booking_dates[$key]['start_time'] = date('h:i A', strtotime($value['start_time']));
            $booking_dates[$key]['end_time'] = date('h:i A', strtotime($value['end_time']));
        }


        // No need of customers list for the edit page
        $customers = [];


        // Get all vendors list
        $vendors_query = User::select('name', 'id')->where('user_type_id', 3)->with(['vendor_details' => function ($query) {

            $query->select('user_id', 'hourly_rate', 'deposit_amount');
        }]);

        // if user_id is not all then add the where
        if ($user_id !== "all") {
            $vendors_query->where('id', $user_id);
        }


        $vendors = $vendors_query->get();


        // Convert to array
        $vendors_array = $vendors->keyBy('id')->toArray();

        // Get all countries
        $countries = CountryModel::orderBy('name', 'asc')->get();

        // Get all resources
        $booking_resources = BookingResource::getAll();
        $booking_resources = $booking_resources->toArray();


        // ---------

        $vendorId = $vendor_user_id;

        // // Get all booking dates of the vendor and pluck the date
        // $old_booking_dates = VendorBooking::select('id')->with('dates')->where('user_id', $vendorId)->get()->pluck('dates')->toArray();

        // // Combine all the dates
        // $old_booking_dates = collect($old_booking_dates)->flatten(1)->toArray();

        // dd($old_booking_dates);

        // ----------

        $vendor_future_bookings = $this->getVendorFutureBookingDates(null, $id);

        return view('admin.veondor_booking.create', compact('page_heading', 'type', 'user_id', 'vendor_user_id', 'id', 'title', 'reference_number', 'total', 'advance', 'order_id', 'booking', 'booking_dates', 'customers', 'vendors', 'vendors_array', 'medias', 'maxImgsAllowed', 'countries', 'booking_resources', 'vendor_future_bookings', 'duration', 'durationArray'));
    }

    public function webGetArtistBookingFutureDates(Request $request)
    {

        $vendorId = $request->vendor_id;

        // Get all booking dates of the vendor
        $future_booking_dates = $this->getVendorFutureBookingDates($vendorId);

        // Return the response
        return response()->success("Future booking dates", $future_booking_dates);
    }


    public function getOldBookingData(Request $request)
    {

        $vendorId = $request->vendor_id;
        $bookingId = $request->booking_id;

        // Get all booking dates of the vendor
        $old_booking_dates = User::find($vendorId)->vendor_booking_dates()->get()->toArray();

        // Return the response
        return response()->success("Old booking dates", $old_booking_dates);
    }



    static function CalculateDurationHours($start_time, $end_time)
    {

        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        $duration_hours = ($end_time - $start_time) / 3600;
        return $duration_hours;
    }


    private function durationSelectBoxArray() {

        $duration = [];

        $duration[] = [
            'value' => 0.5,
            'text' => '30 min'
        ];
        $duration[] = [
            'value' => 1,
            'text' => '1 hour'
        ];
        $duration[] = [
            'value' => 1.5,
            'text' => '1 hour 30 min'
        ];
        $duration[] = [
            'value' => 2,
            'text' => '2 hours'
        ];
        $duration[] = [
            'value' => 2.5,
            'text' => '2 hours 30 min'
        ];
        $duration[] = [
            'value' => 3,
            'text' => '3 hours'
        ];
        $duration[] = [
            'value' => 3.5,
            'text' => '3 hours 30 min'
        ];
        $duration[] = [
            'value' => 4,
            'text' => '4 hours'
        ];
        $duration[] = [
            'value' => 4.5,
            'text' => '4 hours 30 min'
        ];
        $duration[] = [
            'value' => 5,
            'text' => '5 hours'
        ];
        $duration[] = [
            'value' => 5.5,
            'text' => '5 hours 30 min'
        ];
        $duration[] = [
            'value' => 6,
            'text' => '6 hours'
        ];
        $duration[] = [
            'value' => 6.5,
            'text' => '6 hours 30 min'
        ];
        $duration[] = [
            'value' => 7,
            'text' => '7 hours'
        ];
        $duration[] = [
            'value' => 7.5,
            'text' => '7 hours 30 min'
        ];
        $duration[] = [
            'value' => 8,
            'text' => '8 hours'
        ];
        $duration[] = [
            'value' => 8.5,
            'text' => '8 hours 30 min'
        ];
        $duration[] = [
            'value' => 9,
            'text' => '9 hours'
        ];


        return $duration;
    }

    public function store(Request $request)
    {

        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };


        $validator = Validator::make($request->all(), [
            'user_id' => 'required', // vendor id
            // Verify booking_dates array
            'booking_dates' => 'required|array',
            'booking_dates.*.date' => 'required',
            'booking_dates.*.start_time' => 'required',
            'booking_dates.*.end_time' => 'required',
            'customer_id' => !$request->id ? 'required|exists:users,id' : '',
            'reference_no' => !$request->id ? 'required' : '',
            'newMedias.*' => 'image|mimes:jpeg,png,jpg|max:5048',

        ]);

        try {

            $user_id = $request->user_id; // vendor id

            $customer_id = $request->customer_id; // customer id

            // -------- If the user is vendor then on vendor panel we only allow the customer to be added by the email or the phone so validate etc and set the custome id -----

            if (Auth::user()->user_type_id == 3 && !$request->id) {

                // if the option is email then require email else require phone
                if ($request->option == 'email') {
                    $validator = Validator::make($request->all(), [
                        'custm_email' => 'required|email',
                    ]);
                } else {
                    $validator = Validator::make($request->all(), [
                        'custm_dialcode' => 'required',
                        'custm_phone' => 'required',
                    ]);
                }

                // If the validation fails then return the error response
                if ($validator->fails()) {
                    return $returnResponse("0", "Validation error occured", $validator->messages());
                }


                // ______ We are here it means required params are found, now search the customer id by the email or the phone _____

                if ($request->option == 'email') {
                    $user = User::where('email', Str::lower($request->custm_email))->where('user_type_id', 2)->select('id')->first();
                } else {
                    // If the type is phone then search the user by phone
                    $user = User::where('phone', $request->custm_phone)->where('user_type_id', 2)->where('dial_code', $request->custm_dialcode)->select('id')->first();
                }

                // If the user is not found then return the error response
                if (!$user) {
                    return $returnResponse("0", "Customer not found");
                }

                // Set the customer_id
                $customer_id = $user->id;

                // __________________________

            }

            // --------------------------------------



            /**
             * Array containing uploaded media files.
             *
             * Example structure of each element:
             * [
             *     'name' => 'example.mp4', // Name of the uploaded file
             *     'type' => 'image' | 'video',    // media type
             * ]
             *
             * @var array
             */
            $newUploadedMedias = [];

            // Function to delete the new uploaded media
            $deleteNewUploadedMedias = function () use ($newUploadedMedias) {
                foreach ($newUploadedMedias as $media) {
                    VendorBookingMedia::deleteMedia($media['name']);
                }
            };


            // If the validation fails then return the error response
            if ($validator->fails()) {
                return $returnResponse("0", "Validation error occured", $validator->messages());
            }





            $successMsg = "Booking Added Successfully";
            $newOrder = true;

            // If id is not empty then it means it's an update
            if ($request->id) {
                $Order = VendorBooking::with(['medias'])->where('id', $request->id)->where('user_id', $user_id)->first();
                if (!$Order) {
                    return $returnResponse("0", "Booking not found");
                }

                $successMsg = "Booking Updated Successfully";
            } else {
                $Order = new VendorBooking();
                $Order->reference_number = $request->reference_no;
                $Order->status = "created";
                $Order->customer_id = $customer_id;
                $Order->total_paid = 0;
                $Order->tax = 0;
                $Order->discount = 0;
                $Order->total_with_tax = 0;
                $Order->order_id = VendorBooking::generateUniqueOrderId();



            }


            // If booking order not found then validate title
            if (!$Order->bookingOrder) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required',
                ]);

                // If the validation fails then return the error response
                if ($validator->fails()) {
                    return $returnResponse("0", "Validation error occured", $validator->messages());
                }
            }



            // If title is provided then set
            if ($request->title) {
                $Order->title = $request->title;
            }



            $Order->user_id = $user_id;



            // ------------ Upload media, we are doing outside the transaction so save the db lock time to increase the performance ----------


            // if order is found then loop through the $order->medias and ready the image count

            $dbImageCount = 0;


            // If Order is found then get the images count
            if ($Order) {

                $dbImageCount = $Order->medias ? Count($Order->medias->toArray()) : 0;
            }


            // If have the new images then Process and store images and save to the array
            if ($request->hasFile('newMedias')) {
                $maxImages = VendorBookingController::$maxImgsAllowed;

                foreach ($request->file('newMedias') as $photo) {
                    if ($dbImageCount < $maxImages) {
                        $response = single_image_upload($photo, VendorBookingMedia::$mediaFolderName);
                        if ($response['status']) {

                            $newUploadedMedias[] = [
                                'name' => $response['link'],
                                'type' => 'image'
                            ];

                            $dbImageCount++;
                        }
                    } else {
                        break;
                    }
                }
            }

            // -----------------------------------------------------------------------------


            // Start a database transaction
            DB::beginTransaction();

            $Order->duration = $request->duration;
            $Order->total = 0;
            $Order->advance = $Order->user->vendor_details->deposit_amount;
            $Order->hourly_rate = $Order->user->vendor_details->hourly_rate;


            // Save the order
            $Order->save();


                $booking = VendorBooking::with('customer')->where('id', $Order->id)->first();
                $status_not = $booking->status;

                $this->fireBaseNotificationOnNewbooking($booking->customer, $booking->id, $booking->order_id, $status_not);




            // Get the booking dates from the db
            $bookingDates = $Order->dates()->get();

            // Format to have the id as the key
            $bookingDates = $bookingDates->keyBy('id')->toArray();


            // ------- Loop through the booking dates array in the request and if the id contain the new- then ready for bulk insert else update the existing booking date ------
            $newBookingDates = [];
            $updatedBookingDates = [];
            foreach ($request->booking_dates as $key => $value) {

                // If the kewy id contains new- then it means it's a new booking date
                if (strpos($key, 'new-') !== false) {
                    $newBookingDates[] = [
                        'booking_id' => $Order->id,
                        'resource_id' => $value['resource_id'] ?? 1,
                        'date' => $value['date'],
                        'start_time' => VendorBookingController::convertTo24HourFormat($value['start_time']),
                        'end_time' => VendorBookingController::convertTo24HourFormat($value['end_time']),
                    ];
                } else {
                    // If the key id does not contain new- then it means it's an updated booking date
                    $updatedBookingDates[] = [
                        'id' => $key,
                        'resource_id' => $value['resource_id'] ?? 1,
                        'booking_id' => $Order->id,
                        'date' => $value['date'],
                        'start_time' => VendorBookingController::convertTo24HourFormat($value['start_time']),
                        'end_time' => VendorBookingController::convertTo24HourFormat($value['end_time']),
                    ];
                }
            }

            // if new booking dates have item then remove all previous
            if (count($newBookingDates) > 0) {
                // As due to client latest requirement we need only one event in the booking so remove all previous one
                VendorBookingDate::where('booking_id', $Order->id)->delete();
            }

            // ----------------------------------




            // ---------- Loop through the db dates and check if the id is not in the request booking_dates then ready the array of row ids to delete so we will bulk delete ------------

            $deleteBookingDates = [];
            foreach ($bookingDates as $key => $value) {
                if (!array_key_exists($key, $request->booking_dates)) {
                    $deleteBookingDates[] = $key;
                }
            }

            // Bulk delete the booking dates if any
            if (count($deleteBookingDates) > 0) {
                VendorBookingDate::whereIn('id', $deleteBookingDates)->delete();
            }


            // Bulk insert the new booking dates if any
            if (count($newBookingDates) > 0) {
                VendorBookingDate::insert($newBookingDates);
            }


            // Loop through the updated booking dates and update them
            foreach ($updatedBookingDates as $key => $value) {
                $bookingDate = VendorBookingDate::where('id', $value['id'])->where('booking_id', $Order->id)->first();
                if ($bookingDate) {
                    $bookingDate->resource_id = $value['resource_id'];
                    $bookingDate->date = $value['date'];
                    $bookingDate->start_time = $value['start_time'];
                    $bookingDate->end_time = $value['end_time'];
                    $bookingDate->save();
                }
            }


            // if booking dates changed / new then fetch from db and calculate the total and advance
            if ($request->booking_dates) {

                // Get the booking dates from the db
                $bookingDates = $Order->dates()->get();


                $totalHours = 0;
                $total = 0;

                foreach ($bookingDates as $key => $value) {
                    $duration_hours = VendorBookingController::CalculateDurationHours($value->start_time, $value->end_time);
                    $totalHours += $duration_hours;
                    $total += $duration_hours * $Order->hourly_rate;
                }



                // ------ Calculate the rates and update -------


                $Order->total = $total;

                $Order->tax = (config('global.tax_percentage') / 100) * ($total);
                $Order->discount = 0;
                $Order->total_hours = $totalHours;
                $Order->total_without_tax = $Order->total;
                $Order->total_with_tax = $Order->total_without_tax + $Order->tax;
                $Order->disraption = ($Order->total_without_tax * 5) / 100;
                $Order->artist_commission = ($Order->total_without_tax * 54.8) / 100;
                $Order->neworer_commission = ($Order->total_without_tax * 40.2) / 100;
                $Order->gateway = (($Order->total_without_tax * 2.9) / 100) + 1;

                // ---------------------------------------------

                $Order->save();
            }



            // If have the upload medias then Loop through the $newUploadedMedias and save them to the database
            if ($newUploadedMedias) {
                foreach ($newUploadedMedias as $media) {
                    $Order->medias()->create(['filename' => $media['name']]);
                }
            }


            // Commit the transaction
            DB::commit();


            return $returnResponse(1, $successMsg, []);
        } catch (\Exception $e) {

            // Rollback the transaction in case of any exception
            DB::rollback();

            $deleteNewUploadedMedias();

            // Handle the exception or log the error
            $error = $e->getMessage();

            return $returnResponse(0, "Something went wrong", [], $error);
        }
    }


    // Update the order status
    public function change_status(Request $request, $type, $user_id)
    {
        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };

        $validator = Validator::make($request->all(), [
            'orderId' => 'required',
            'status' => 'required'
        ]);

        // If the validation fails then return the error response
        if ($validator->fails()) {
            return $returnResponse("0", "Validation error occured", $validator->messages());
        }


        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return $returnResponse("0", "Unauthorized access");
        }



        // Find booking order by the order id and vendor id
        $order = VendorBooking::with('customer')->where('id', $request->orderId)
            ->where('user_id', $user_id)
            ->first();

        if ($order) {
            $order->status = $request->status;
            $order->save();


            // Firebase notification
            $this->fireBaseNotificationOnStatusUpdate($order->customer, $order->id, $order->order_id, $request->status);

            return $returnResponse("1", "Status updated successfully!", []);
        } else {
            return $returnResponse("0", "Order not found!", []);
        }
    }


    public function cancelBooking(Request $request, $type, $user_id)
    {
        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };


        $validator = Validator::make($request->all(), [
            'orderId' => 'required',
        ]);

        // If the validation fails then return the error response
        if ($validator->fails()) {
            return $returnResponse("0", "Validation error occured", $validator->messages());
        }


        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return $returnResponse("0", "Unauthorized access");
        }


        $cancelRemarks = $request->remarks ?? "";


        $refundMade = $request->is_refund ?? 0;

        $refundFile = $request->refund_file ?? null;

        if ($refundMade) {

            // upload file
            $response = image_upload($request, VendorBookingMedia::$mediaFolderName, 'file');
            if ($response['status']) {
                $refundFile = $response['link'];
            } else {
                return $returnResponse("0", "Error occured while uploading refund file", $response['message']);
            }
        }


        // Find booking order by the order id and vendor id
        $order = VendorBooking::with('customer')->where('id', $request->orderId)
            ->where('user_id', $user_id)
            ->first();

        if ($order) {
            $order->status = "cancelled";
            $order->is_refund_made = $refundMade;
            $order->refund_file = $refundFile;
            $order->cancel_remarks = $cancelRemarks;
            $order->save();


            // // Send email
            // $mailbody =  view("web.emai_templates.verify_mail",compact('user','link'));
            // $res = send_email($order->customer->email,'Your Membership To The My events Marketplace Has Been Approved',$mailbody);
            // if($res){}

            // Firebase notification
            $this->fireBaseNotificationOnStatusUpdate($order->customer, $order->id, $order->order_id, $request->status);

            return $returnResponse("1", "Booking cancelled successfully!", []);
        } else {
            return $returnResponse("0", "Order not found!", []);
        }
    }

    public function deleteBooking()
    {
        // Find booking order by the order id and vendor id
        $order = VendorBooking::query()->delete();

        echo 'bookings deleted';
    }


    public function delete_image(Request $request)
    {

        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };

        $validator = Validator::make($request->all(), [
            'imageId' => 'required',
            'orderId' => 'required',
            'user_id' => 'required'
        ]);

        $status = "0";
        $message = "";
        $errors = [];

        if ($validator->fails()) {

            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
        } else {

            $userId = $request->user_id;

            // Find the order by the id and user_id
            $order = VendorBooking::where('id', $request->orderId)
                ->where('user_id', $userId)
                ->first();

            $media = $order->medias()->find($request->imageId);

            if ($media) {

                if ($media->delete()) {
                    VendorBookingMedia::deleteMedia($media->filename);
                };


                $status = "1";
                $message = "Media deleted successfully";
            } else {
                $status = "0";
                $message = "Media not found";
            }
        }

        echo $returnResponse($status, $message, $errors);
    }




    public static function convertTo24HourFormat($time)
    {

        // Check if the time string contains a colon
        if (strpos($time, ':') === false) {
            // If it doesn't, append ':00' to indicate minutes
            $time .= ':00';
        }

        return date('H:i', strtotime($time));
    }


    private function getVendorFutureBookingDates($vendorId = null, $excludeBookingId = null)
{
    // Get all future VendorBooking dates of the vendor, the dates relation dates should be in the future
    $bookingDates_query = VendorBooking::with(['dates', 'transactions', 'customer', 'vendor', 'user'])
        ->where('status', '!=', 'cancelled')
        ->whereHas('dates', function ($query) {
            $query->where('date', '>=', date('Y-m-d'));
        });

    // If vendor is provided
    if ($vendorId) {
        $bookingDates_query->where('user_id', $vendorId);
    }

    // If the exclude booking id is provided then exclude the booking id
    if ($excludeBookingId) {
        $bookingDates_query->where('id', '!=', $excludeBookingId);
    }

    // Execute the query to get the results
    $bookingDates = $bookingDates_query->get();

    // Map the dates with the status
    $mappedDates = $bookingDates->flatMap(function ($booking) {
        return $booking->dates->map(function ($date) use ($booking) {
            $date->status = $booking->status;
            $date->customer_name = $booking->customer->name;
            $date->user_name = $booking->user->name;
            return $date;
        });
    });

    return $mappedDates;
}



    private function findAvailableResource($data, $resource_ids, $date, $start_time, $end_time)
    {
        $requestedStartTime = strtotime($date . ' ' . $start_time);
        $requestedEndTime = strtotime($date . ' ' . $end_time);

        foreach ($resource_ids as $resource_id) {
            $isAvailable = true;

            foreach ($data as $booking) {
                if ($booking['resource_id'] == $resource_id && $booking['date'] == $date) {
                    $bookingStartTime = strtotime($booking['date'] . ' ' . $booking['start_time']);
                    $bookingEndTime = strtotime($booking['date'] . ' ' . $booking['end_time']);

                    // Check if the requested time range overlaps with the booked time range
                    if ($requestedStartTime < $bookingEndTime && $requestedEndTime > $bookingStartTime) {
                        $isAvailable = false;
                        break;
                    }
                }
            }

            if ($isAvailable) {
                return $resource_id;
            }
        }

        return null; // No available resources found
    }


    public function apiBookingPay(Request $request)
    {



        $data = ["paymentIntent" => null];
        $message = "Payment successful";

        // Validate the request
        $validator = Validator::make($request->all(), [
            'reference_number' => 'required',
            'is_wallet' => 'boolean',
            'is_stripe' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->error("Validation error", $validator->messages());
        }

        // if the is_wallet and is_stripe both are true then return error
        if ($request->is_wallet && $request->is_stripe) {
            return response()->error("Can not pay with both wallet and stripe", []);
        }

        // if the is_wallet and is_stripe both are false then return error
        if (!$request->is_wallet && !$request->is_stripe) {
            return response()->error("Please select payment method", []);
        }

        $customerId = Auth::id();

        // uppercase
        $request->reference_number = strtoupper($request->reference_number);


        // Get the booking by the reference number and the customer id
        $booking = VendorBooking::with('customer')->where('reference_number', $request->reference_number)->where('customer_id', $customerId)->first();


        if (!$booking) {
            return response()->error("Booking not found", []);
        }


        // If the booking is status is not created and payment then return error
        if ($booking->status != "created" && $booking->status != "payment") {
            return response()->error("Can not pay on this booking", []);
        }


        // Get the customer with the customer deetails
        $customer = User::where('id', $customerId)->with('customerUserDetail')->first();


        // Get the oustanding amount data
        $crntStageData = $booking->crnt_stage_outstanding_amount;
        $oustandingAmount = $crntStageData["amount"];

        if($oustandingAmount<1){
            return response()->error("Amount can not be less than 1", []);
        }

        // Transaction
        DB::beginTransaction();


        // ----------- If wallet payment -----------

        // if it's wallet payment
        if ($request->is_wallet) {


            // If the wallet balance is less than the total amount then return error
            if ($customer->customerUserDetail->wallet_balance < $oustandingAmount) {

                return response()->error("Insufficient balance", []);

            }



            // Deduct the amount from the wallet
            $customer->customerUserDetail->wallet_balance -= $oustandingAmount;
            $customer->customerUserDetail->save();


            // _______ Booking Status and update total_paid _______

            // Status based on current stage type.
            $status = $booking->status== "created" ? "payment" : "completed";


            // As it's wallet payment which is success, so add the total amount to the total paid
            $booking->total_paid += $oustandingAmount;

            $booking->last_payment_method = "wallet";


            // Update the booking status to paid
            $booking->status = $status;
            $booking->save();

            // Firebase notify
            $this->fireBaseNotificationOnStatusUpdate($booking->customer, $booking->id, $booking->order_id, $status);

            // _____________________________________________________
        }

        // --------------------



        // ------------ Transaction ----------

        // Transaction type based on the type
        $transactionType = $crntStageData["type"] == "advance" ? Transaction::$type_Advance : Transaction::$type_Full;

        $paymentMethod = $request->is_wallet ? Transaction::$payment_method_Wallet : Transaction::$payment_method_Stripe;



        // ____ Create the transaction for current user ___
        $currentUserTransaction = new Transaction();
        $currentUserTransaction->order_id = $booking->id;
        $currentUserTransaction->vendor_id = $booking->user_id;
        $currentUserTransaction->customer_id = $customerId;
        $currentUserTransaction->transaction_id = Transaction::generateTransactionId();
        $currentUserTransaction->amount = $oustandingAmount;
        $currentUserTransaction->type = $transactionType;
        $currentUserTransaction->status = Transaction::$payment_status_Success;
        $currentUserTransaction->payment_method = $paymentMethod;


        //  **** If it's stripe payment then create the stripe payment intent ****
        if ($request->is_stripe) {
            // Clean old temp transactions
            TempTransaction::cleanOldTransactions();


            $stripeData = PaymentStripe::generatePaymentIntent($oustandingAmount, "Booking {$transactionType} Payment", [
                'booking_id' => $booking->id,
                'customer_id' => $customerId,
                'vendor_id' => $booking->user_id,
            ]);


            $currentUserTransaction->p_transaction_id = $stripeData['paymentIntent']["id"];
            $currentUserTransaction->p_status = Transaction::$payment_status_Pending;
            $currentUserTransaction->p_data = json_encode([
                'clientSecret' => $stripeData['paymentIntent']->client_secret,
            ]);

            // We will not save the transaction here, we will save it after the payment success


            // Generate and save the temp transaction
            $transaction = new TempTransaction();
            $transaction->type = TempTransaction::$type_stripe;
            $transaction->p_id = $stripeData['paymentIntent']["id"];
            $transaction->p_status = TempTransaction::$payment_status_Pending;
            $transaction->transaction_data = json_encode($currentUserTransaction->toArray());
            $transaction->save();


            // Ready the data
            $data = $stripeData;

            $message = "Payment intent created successfully";
        }

        // ************


        // if it's wallet payment then save the transaction as for the wallet transaction it's success
        if ($request->is_wallet) {

            $currentUserTransaction->save();
        }


        // _______________


        // -----------------------------------


        // Commit the transaction
        DB::commit();


        return response()->success($message, $data);
    }


    public function apiBookingStripeSuccess(Request $request)
    {

        $data = [];
        $message = "Payment successful";

        try {

            // current logged in customer id
            $customerId = Auth::id();

            // Validate the request
            $validator = Validator::make($request->all(), [
                'payment_intent_id' => 'required',
                'payment_method' => 'required|in:stripe_apple,stripe_google,stripe_card',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation error", $validator->messages());
            }

            // Get the temp transaction by the payment intent id
            $tempTransaction = TempTransaction::where('p_id', $request->payment_intent_id)->first();

            if (!$tempTransaction) {
                return response()->error("Transaction not found", []);
            }

            // Get the transaction data
            $transactionData = json_decode($tempTransaction->transaction_data, true);

            // if the customer id not matched
            if ($transactionData['customer_id'] != $customerId) {
                return response()->error("Unauthorized access", []);
            }

            // Get the booking by the booking id
            $booking = VendorBooking::with('customer')->where('id', $transactionData['order_id'])->first();

            if (!$booking) {
                return response()->error("Booking not found", []);
            }


            $crntStageData = $booking->crnt_stage_outstanding_amount;
            // Status based on current stage type.
            $status = $booking->status == "created" ? "payment" : "completed";


            DB::beginTransaction();


            // As it's stripe payment which is success, so add the total amount to the total paid
            $booking->total_paid += $transactionData['amount'];

            // Update the booking status to paid
            $booking->status = $status;

            $booking->last_payment_method = $request->payment_method;

            // Save the booking
            $booking->save();


            $transactionData = (array)$transactionData;

            // payment method
            switch ($request->payment_method) {
                case 'stripe_apple':
                    $transactionData['payment_method'] = Transaction::$payment_method_StripeApple;
                    break;
                case 'stripe_google':
                    $transactionData['payment_method'] = Transaction::$payment_method_StripeGoogle;
                    break;
                case 'stripe_card':
                    $transactionData['payment_method'] = Transaction::$payment_method_StripeCard;
                    break;
            }

            // Save the real transaction
            $transaction = new Transaction();
            $transaction->fill($transactionData);
            $transaction->save();

            // Delete the temp transaction
            $tempTransaction->delete();


            // Commit the transaction
            DB::commit();


            // Firebase notify
            $this->fireBaseNotificationOnStatusUpdate($booking->customer, $booking->id, $booking->order_id, $status);

            return response()->success($message, $data);
        } catch (\Throwable $th) {

            // Rollback the transaction
            DB::rollback();

            return response()->error("Some error occured", $th->getMessage());
        }
    }



    // public function vendorListsForBooking(Request $request) {

    //     // If user is not admin then redirect to unauthorized access
    //     if (Auth::user()->user_type_id !== 1) {
    //         return redirect()->route('admin.restricted_page');
    //     }

    //     // Get all vendor lists
    //     $vendors = User::where('user_type_id', 3)->get();

    //     return view('admin.veondor_booking.vendor_lists', compact('vendors'));

    // }



    public function apiGetAllBookings(Request $request)
    {

        $limit = $request->limit ?? 10;

        try {

            // Get current logged in user with relation bookings
            $user = Auth::user();

            $balance = CustomerUserDetail::where('user_id', $user->id)->value('wallet_balance');
            // ->where('status', '!=', 'created')
            $bookings = VendorBooking::where('customer_id', $user->id)->with(
                [
                    'vendor' => function ($query) {
                        $query->select(['id', 'name', 'user_image']);
                    },
                    'vendor.vendor_details.category',
                ]
            )->orderBy("id", "desc")->paginate($limit);

            if ($bookings->isEmpty()) {
                return response()->success("No Bookings found");
            }

            foreach($bookings as $key_b => $value_b)
            {
                $bookings[$key_b]->status = order_statuses($value_b->status);
            }

            $bookings = cleanPaginationResultArray($bookings->toArray());

            $bookings = convert_all_elements_to_string($bookings);


            return response()->success("Bookings found", $bookings, [
                "wallet_balance" => $balance,
            ]);
        } catch (QueryException $e) {
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    public function apiGetBookingsByIdOrReference(Request $request)
    {

        try {

            // Validate the request that we should provide the id or the reference_number
            $validator = Validator::make($request->all(), [
                'booking_id' => 'required_without:reference_number',
                'reference_number' => 'required_without:booking_id',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation error", $validator->messages());
            }

            $id = $request->booking_id;
            $reference_number = $request->reference_number;

            // Get current logged in user with relation bookings
            $user = Auth::user();

            $balance = CustomerUserDetail::where('user_id', $user->id)->value('wallet_balance');

            $booking_query = VendorBooking::where('customer_id', $user->id)->with(
                [
                    'dates.VendorBookingResource',
                    'medias',
                    'review.customer' => function ($query) {
                        $query->select(['id', 'name']);
                    },
                    'vendor' => function ($query) {
                        $query->select(['id', 'name', 'user_image']);
                    },
                    'vendor.vendor_details.category',
                ]
            );

            // If id is provided then search by id
            if ($id) {
                $booking =  $booking_query->where('id', $id)->first();
            }

            // If reference_number is provided then search by reference_number
            if ($reference_number) {
                $booking = $booking_query->where('reference_number', $reference_number)->first();
            }


            if (!$booking) {
                return response()->error("No Bookings found", []);
            }

            $booking->status = order_statuses($booking->status);



            $booking = convert_all_elements_to_string($booking->toArray());

            $booking["wallet_balance"] = $balance;

            if ($booking["review"] == "") {
                $booking["review"] = (object)[];
            }

            if ($booking["before_reschedule_dates"] == "") {
                $booking["before_reschedule_dates"] = (object)[];
            }


            // Remove the josn because in the model we are decoding the json and sending in the key reschedule_policies
            try {
                unset($booking["vendor"]['vendor_details']['r_policy']);
            } catch (\Exception $e) {
            }


            return response()->success("Bookings found", $booking);
        } catch (QueryException $e) {
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    public function apiGiveBookingReview(Request $request, $booking_id)
    {


        try {

            $validator = Validator::make($request->all(), [
                'rating' => 'required|numeric|min:1|max:5',
                'review' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation error", $validator->messages());
            }


            // Get current logged in user
            $user = Auth::user();

            $booking = VendorBooking::select('user_id')->where('customer_id', $user->id)->where('id', $booking_id)->first();

            if (!$booking) {
                return response()->error("No Bookings found", []);
            }

            $vendor_id = $booking->user_id;

            // if rating already exist by the user and the booking order then return error
            $rating = VendorRating::where('booking_id', $booking_id)->where('vendor_id', $vendor_id)->where('user_id', $user->id)->exists();
            if ($rating) {
                return response()->error("Rating already exist", []);
            }


            // Get the user
            $vendor = User::with(['vendorRatings', 'vendor_details'])->where('id', $vendor_id)->first();

            // Trsanction
            DB::beginTransaction();


            // New rating object
            $rating = new VendorRating();
            $rating->booking_id = $booking_id;
            $rating->vendor_id = $vendor_id;
            $rating->user_id = $user->id;
            $rating->rating = $request->rating;
            $rating->review = $request->review;
            $rating->save();


            $vendor->updateVendorTotalRatings();


            // Commit
            DB::commit();

            return response()->success("Review added successfully", []);
        } catch (QueryException $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    public function apiRecheduleBookingDates(Request $request, $onlyCheck = true)
    {


        // Validate dates array of object {old_date, new_date, id}
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|numeric',
            'dates' => 'required|array',
            'dates.*.date' => 'required|date',
            'dates.*.id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->error("Validation error", $validator->messages());
        }


        $customerId = Auth::id();

        // Get the vendor booking by id
        $booking = VendorBooking::with(['vendor.vendor_details', 'dates'])->where('id', $request->booking_id)->where('customer_id', $customerId)->first();

        if (!$booking) {
            return response()->error("Booking not found", []);
        }
        $booking_date=$booking->dates->first();
        // If the booking is status is not created and payment then return error
        if ($booking->status != "payment") {
            return response()->error("Can not pay on this booking because you can only reschdule if the status deoposit amount payed", []);
        }


        $old_dates = $booking->dates->keyBy('id')->toArray();


        // ------- Ready new dates -------------

        // Get dates as array the key should be the id
        $new_dates = $booking->dates->keyBy('id')->toArray();

        // Looop through the request dates and update the dates in the new_dates
        foreach ($request->dates as $key => $value) {

            if (!array_key_exists($value['id'], $new_dates)) {
                return response()->error($value['id'] . " is Invalid date id", []);
            }

            $new_dates[$value['id']]['date'] = $value['date'];
        }


        // ------------------------------------

        $rescheduleRate = 0;
        //$reschedule_policies = $booking->vendor->vendor_details->reschedule_policies;
        $r_policies = Setting::where('meta_key', 'return_policies_hours')->first();

        // ------ Check for the future availability date, amount etc return error if any or update the resource_id ------

        // Get the BookingResource ids
        $resource_ids = BookingResource::pluck('id')->toArray();

        // Get future booking dates of the vendor
        $futureBookingDates = $this->getVendorFutureBookingDates($booking->user_id);
        // loop through the $request->dates and get the available resource and update the new_dates

        foreach ($request->dates as $key => $value) {

            $id = $value['id'];

            $value = $new_dates[$id];

            $date = $value['date'];
            $start_time = $value['start_time'];
            $end_time = $value['end_time'];

            $availableResource = $this->findAvailableResource($futureBookingDates, $resource_ids, $date, $start_time, $end_time);
            // If no available resource found then return error
            if (!$availableResource) {

                return response()->error("No available slot for the date $date within the specified time range of $start_time to $end_time.", [
                    ["id" => $id]
                ]);
            }

            $new_dates[$id]['resource_id'] = $booking_date->resource_id;


            // _______________ Reschedule Rate ______________

            $current = now();
            $new_date = new DateTime("$date $start_time");
            //dd($date.' '.$start_time);
            $diff = $new_date->diff($current)->days;

            $givenDateTime = new DateTime("$booking_date->date $booking_date->start_time", new \DateTimeZone('Asia/Dubai'));
            //dd($givenDateTime);
            // Get the current time
            $currentDateTime = new DateTime("now", new \DateTimeZone('Asia/Dubai'));

            $interval = $currentDateTime->diff($givenDateTime);

            $hours = ($interval->days * 24) + $interval->h;


            $return_policies=[];

            if(!empty($r_policies)){
                $return_policies = json_decode($r_policies->meta_value);
            }
            $reschedule_policy = null;
            foreach($return_policies as $return_policy){
                if($return_policy->hours>=$hours){
                    $reschedule_policy = $return_policy;
                    break;
                }
            }
            if($reschedule_policy){
            $reschedule_policy_amount=$booking->advance*$reschedule_policy->amount/100;
            $rescheduleRate +=$reschedule_policy_amount;
            }

            // $rescheduleRate += $reschedule_policy ? $reschedule_policy['amount'] : 0;
            // $diff = abs($diff);
            // $diff = round($diff);
            // // Loop through the reschedule policies and check if the days are in the range then get it

            // foreach ($reschedule_policies as $key => $value1) {
            //     if ($diff >= $value1['dayStart'] && $diff <= $value1['dayEnd']) {
            //         $reschedule_policy = $value1;
            //         break;
            //     }
            // }

            // $rescheduleRate += $reschedule_policy ? $reschedule_policy['amount'] : 0;

            // _______________

        }


        // ----------------------------------------------------------------------------------



        // // ------- Calculate the reschedule rate -----------


        // // loop through the dates and get the reschedule rate
        // $rescheduleRate = 0;




        // // Check how many days passed by the created_at date
        // $created_at = $booking->created_at;
        // $now = now();
        // $diff = $now->diffInDays($created_at, false);
        // $diff = abs($diff);
        // $diff = round($diff);


        // // Get the reschedule policies
        // $reschedule_policies = $booking->vendor->vendor_details->reschedule_policies;

        // // Loop through the reschedule policies and check if the days are in the range then get it
        // $reschedule_policy = null;
        // foreach ($reschedule_policies as $key => $value) {
        //     if ($diff >= $value->dayStart && $diff <= $value->dayEnd) {
        //         $reschedule_policy = $value;
        //         break;
        //     }
        // }


        // $rescheduleRate = $reschedule_policy ? $reschedule_policy->amount : 0;

        // // ------------------------------------------------

        // If not check then ready temp data and save
        if (!$onlyCheck) {

            // Temp reschedule data
            $tempRescheduleData = [
                'new_dates' => $new_dates,
                'reschedule_rate' => $rescheduleRate,
                'reschedule_policy' => $reschedule_policy,
            ];


            // Update the booking with the temp reschedule data
            $booking->temp_reschedule_data = json_encode($tempRescheduleData);

            $booking->save();
        }





        $data = [
            "amount" => $rescheduleRate,
        ];


        if ($onlyCheck) {
            return response()->success("Rates", $data);
        }


        // Return rate if not only check
        return $rescheduleRate;
    }


    public function apiRecheduleBookingDatesPay(Request $request)
    {

        // Call the apiRecheduleBookingDates function to create the temp order
        $this->apiRecheduleBookingDates($request, false);


        $data = ["paymentIntent" => null];
        $message = "Payment successful";

        // Validate the request
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'is_wallet' => 'boolean',
            'is_stripe' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->error("Validation error", $validator->messages());
        }

        // if the is_wallet and is_stripe both are true then return error
        if ($request->is_wallet && $request->is_stripe) {
            return response()->error("Can not pay with both wallet and stripe", []);
        }

        // if the is_wallet and is_stripe both are false then return error
        if (!$request->is_wallet && !$request->is_stripe) {
            return response()->error("Please select payment method", []);
        }

        $customerId = Auth::id();


        // Get the booking by the reference number and the customer id
        $booking = VendorBooking::with(['customer', 'dates.VendorBookingResource'])->where('id', $request->booking_id)->where('customer_id', $customerId)->first();


        if (!$booking) {
            return response()->error("Booking not found", []);
        }


        // If the booking is status is not created and payment then return error
        if ($booking->status != "payment") {
            return response()->error("Can not pay on this booking because you can only reschdule if the status is Deposit Payed", []);
        }


        // Get the customer with the customer deetails
        $customer = User::where('id', $customerId)->with('customerUserDetail')->first();

        if (!$booking->temp_reschedule_data || $booking->temp_reschedule_data == "") {
            return response()->error("No reschedule data found, please call the Reschedule Booking Dates Validitiy api first!", []);
        }

        $reScheduleData = json_decode($booking->temp_reschedule_data, true);

        // Get the oustanding amount data
        $oustandingAmount = $reScheduleData["reschedule_rate"];


        // Transaction
        DB::beginTransaction();


        // ----------- If wallet payment -----------

        // if it's wallet payment
        if ($request->is_wallet) {


            // If the wallet balance is less than the total amount then return error
            if ($customer->customerUserDetail->wallet_balance < $oustandingAmount) {
                return response()->error("Insufficient balance", []);
            }



            // Deduct the amount from the wallet
            $customer->customerUserDetail->wallet_balance -= $oustandingAmount;
            $customer->customerUserDetail->save();


            // _______ Booking Status and update total_paid _______

            // Status based on current stage type.
            $status = "payment";

            // As it's wallet payment which is success, so add the total amount to the total_rschdl_paid paid
            $booking->total_rschdl_paid += $oustandingAmount;

            $booking->last_payment_method = "wallet";


            // Update the booking status to paid
            $booking->temp_reschedule_data = "";
            $booking->before_reschedule_dates = json_encode($booking->dates->toArray());
            $booking->status = $status;
            $booking->is_rescheduled = 1;
            $booking->save();


            // sync dates
            $booking->dates()->delete();
            $booking->dates()->createMany($reScheduleData["new_dates"]);

            // _____________________________________________________

        }

        // --------------------



        // ------------ Transaction ----------

        // Transaction type based on the type
        $transactionType = Transaction::$type_Reschedule;

        $paymentMethod = $request->is_wallet ? Transaction::$payment_method_Wallet : Transaction::$payment_method_Stripe;



        // ____ Create the transaction for current user ___
        $currentUserTransaction = new Transaction();
        $currentUserTransaction->order_id = $booking->id;
        $currentUserTransaction->vendor_id = $booking->user_id;
        $currentUserTransaction->customer_id = $customerId;
        $currentUserTransaction->transaction_id = Transaction::generateTransactionId();
        $currentUserTransaction->amount = $oustandingAmount;
        $currentUserTransaction->type = $transactionType;
        $currentUserTransaction->status = Transaction::$payment_status_Success;
        $currentUserTransaction->payment_method = $paymentMethod;


        //  **** If it's stripe payment then create the stripe payment intent ****
        if ($request->is_stripe) {

            // Clean old temp transactions
            TempTransaction::cleanOldTransactions();


            $stripeData = PaymentStripe::generatePaymentIntent($oustandingAmount, "Booking {$transactionType} Payment", [
                'booking_id' => $booking->id,
                'customer_id' => $customerId,
                'vendor_id' => $booking->user_id,
            ]);


            $currentUserTransaction->p_transaction_id = $stripeData['paymentIntent']["id"];
            $currentUserTransaction->p_status = Transaction::$payment_status_Pending;
            $currentUserTransaction->p_data = json_encode([
                'clientSecret' => $stripeData['paymentIntent']->client_secret,
            ]);

            // We will not save the transaction here, we will save it after the payment success


            // Generate and save the temp transaction
            $transaction = new TempTransaction();
            $transaction->type = TempTransaction::$type_stripe;
            $transaction->p_id = $stripeData['paymentIntent']["id"];
            $transaction->p_status = TempTransaction::$payment_status_Pending;
            $transaction->transaction_data = json_encode($currentUserTransaction->toArray());
            $transaction->save();


            // Ready the data
            $data = $stripeData;

            $message = "Payment intent created successfully";
        }

        // ************


        // if it's wallet payment then save the transaction as for the wallet transaction it's success
        if ($request->is_wallet) {

            $currentUserTransaction->save();
        }


        // _______________


        // -----------------------------------


        // Commit the transaction
        DB::commit();


        return response()->success($message, $data);
    }

    public function apiRecheduleBookingDatesStripeSuccess(Request $request)
    {

        $data = [];
        $message = "Payment successful";

        try {

            // current logged in customer id
            $customerId = Auth::id();

            // Validate the request
            $validator = Validator::make($request->all(), [
                'payment_intent_id' => 'required',
                'payment_method' => 'required|in:stripe_apple,stripe_google,stripe_card',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation error", $validator->messages());
            }

            // Get the temp transaction by the payment intent id
            $tempTransaction = TempTransaction::where('p_id', $request->payment_intent_id)->first();

            if (!$tempTransaction) {
                return response()->error("Transaction not found", []);
            }

            // Get the transaction data
            $transactionData = json_decode($tempTransaction->transaction_data, true);

            // if the customer id not matched
            if ($transactionData['customer_id'] != $customerId) {
                return response()->error("Unauthorized access", []);
            }

            // Get the booking by the booking id
            $booking = VendorBooking::with(['customer', 'dates.VendorBookingResource'])->where('id', $transactionData['order_id'])->first();

            if (!$booking) {
                return response()->error("Booking not found", []);
            }

            if (!$booking->temp_reschedule_data || $booking->temp_reschedule_data == "") {
                return response()->error("No reschedule data found, please call the Reschedule Booking Dates Validitiy api first!", []);
            }

            $reScheduleData = json_decode($booking->temp_reschedule_data, true);

            // Get the oustanding amount data
            $oustandingAmount = $reScheduleData["reschedule_rate"];


            $status = "payment";


            DB::beginTransaction();


            // Update the booking status to paid
            $booking->temp_reschedule_data = "";
            $booking->total_rschdl_paid += $oustandingAmount;
            $booking->before_reschedule_dates = json_encode($booking->dates->toArray());
            $booking->status = $status;
            $booking->is_rescheduled = 1;
            $booking->save();

            // sync dates
            $booking->dates()->delete();
            $booking->dates()->createMany($reScheduleData["new_dates"]);



            $transactionData = (array)$transactionData;

            // payment method
            switch ($request->payment_method) {
                case 'stripe_apple':
                    $transactionData['payment_method'] = Transaction::$payment_method_StripeApple;
                    break;
                case 'stripe_google':
                    $transactionData['payment_method'] = Transaction::$payment_method_StripeGoogle;
                    break;
                case 'stripe_card':
                    $transactionData['payment_method'] = Transaction::$payment_method_StripeCard;
                    break;
            }

            // Save the real transaction
            $transaction = new Transaction();
            $transaction->fill($transactionData);
            $transaction->save();

            // Delete the temp transaction
            $tempTransaction->delete();


            // Commit the transaction
            DB::commit();


            return response()->success($message, $data);
        } catch (\Throwable $th) {

            // Rollback the transaction
            DB::rollback();

            return response()->error("Some error occured", $th->getMessage());
        }
    }


    public function fireBaseNotificationOnStatusUpdate($customer_obj, $booking_id, $order_id, $status)
    {

        $orig_status = order_statuses($status);
        $status = order_statuses($status);

        return  prepare_notification($customer_obj, "# $order_id", "Your booking status has been updated to $status", $orig_status, "booking", $booking_id);
    }

    public function fireBaseNotificationOnNewbooking($customer_obj, $booking_id, $order_id, $status)
    {

        $orig_status = "";

        return  prepare_notification($customer_obj, "# $order_id", "New booking created", $status, "new_booking", $booking_id);
    }



    private function excelReporting($queryDb)
    {

        $list = $queryDb->get();
        $rows = array();
        $i = 1;

        foreach ($list as $key => $val) {

            $haveOrders = $val->bookingOrder ? true : false;

            $rows[$key]['i'] = $i;
            $rows[$key]['refrence_no'] = $val->reference_number;
            $rows[$key]['total'] = $val->total;
            $rows[$key]['advance'] = $val->advance;
            $rows[$key]['name'] = $val->user->name;
            $rows[$key]['email'] = $val->user->email;
            $rows[$key]['customer_name'] = $haveOrders ? $val->bookingOrder->customer->name : '';
            $rows[$key]['customer_email'] = $haveOrders ? $val->bookingOrder->customer->email : "";
            $rows[$key]['order_id'] = $haveOrders ? $val->bookingOrder->order_id : "";
            $rows[$key]['status'] = $haveOrders ? $val->bookingOrder->status : "";
            $rows[$key]['total_amount'] = $val->total_without_tax . " AED";
            $rows[$key]['disruption_fee'] = number_format($val->disraption, 2, '.', '');
            $rows[$key]['artist_commission'] = number_format($val->artist_commission, 2, '.', '');
            $rows[$key]['new_order_commission'] = number_format($val->neworer_commission, 2, '.', '');
            $rows[$key]['gateway_fees'] = number_format($val->gateway, 2, '.', '');
            $rows[$key]['taxes_and_charges'] = $val->tax . " AED";
            $rows[$key]['net_total'] = $val->total_with_tax . " AED";
            $rows[$key]['booking_date_time'] = "";

            // Loop through the booking dates and append the date and time with line break
            foreach ($val->dates as $key2 => $val2) {
                $rows[$key]['booking_date_time'] .= web_date_in_timezone($val2->date, 'd-m-y') . ' ' . date('h:i A', strtotime($val2->start_time)) . ' - ' . date('h:i A', strtotime($val2->end_time)) . "\n";
            }

            $rows[$key]['created_date'] = web_date_in_timezone($val->created_at, 'd-m-y h:i A');


            $i++;
        }

        $headings = [
            "#",
            "Refrence No",
            "Total",
            "Advance",
            "Artist Name",
            "Artist Email",
            "Customer Name",
            "Customer Email",
            "Order Id",
            "Order Status",
            "Total amount",
            "Disruption Fee",
            "Artist commission",
            "New oder commission",
            "Gateway Fees",
            "Taxes and charges",
            "Net total",
            "Booking Date & Time",
            "Created Date",
        ];


        // Generate filename
        $file_name = 'artist_bookings_' . date('d_m_Y_h_i_s') . '.xlsx';


        return ExcelExporter::exportToExcel($headings, $rows, $file_name);
    }
    public function taxReport(Request $request, $type, $user_id)
    {


        $reporting = $request->reporting ?? null;

        $restrictedPageRoute = route_name_admin_vendor($type, 'restricted_page');


        // If it's not a reporting page then check the permission
        if (!$reporting && !get_user_permission('vendors_booking', 'r')) {
            return redirect()->route($restrictedPageRoute);
        } else if ($reporting && !get_user_permission('reporting_vendors_booking', 'r')) {
            return redirect()->route($restrictedPageRoute);
        }


        $refrence_no = $request->refrence_no ?? null;
        $order_id = $request->order_id ?? null;
        $artist_name = $request->artist_name ?? null;
        $customer_name = $request->customer_name ?? null;
        $order_status = $request->order_status ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $export = $request->export ?? null;
        $category_id = $request->category_id ?? null;

        $page_heading = "TAX " . ($reporting ? " Report" : "");

        // Ger vendorId variable value from  request query
        $vendorId = request()->query('vendorid');
        $editstatus = request()->query('editstatus');

        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route($restrictedPageRoute);
        }


        $categories = Categories::where('active', 1)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        $artist = VendorUserDetail::select('user_id')->where('category_id', $category_id)->pluck('user_id')->toArray();


        // Get the bookings by the user i
        $query = VendorBooking::with(['user', 'dates', 'customer']);


        if ($user_id !== "all") {
            $query->where('user_id', $user_id);
        }

        if (!empty($artist) && count($artist) > 0) {
            $query->whereIn('user_id', $artist);
        }

        if ($refrence_no) {
            $query->whereRaw('LOWER(reference_number) like ?', ['%' . strtolower($refrence_no) . '%']);
        }


        if ($order_id) {
            $query->whereRaw('LOWER(order_id) like ?', ['%' . strtolower($order_id) . '%']);
        }


        // If artistname lowercase for search
        if ($artist_name) {
            $query->whereHas('user', function ($q) use ($artist_name) {
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($artist_name) . '%']);
            });
        }

        if ($customer_name) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($customer_name) . '%']);
            });
        }


        if ($order_status) {
            $query->where('status', $order_status);
        }

        // from date
        if ($from_date != '') {
            $query->where('created_at', '>=', gmdate('Y-m-d H:i:s', strtotime($from_date . ' 00:00:00')));
        }

        // If the to_date is not null then search the to_date
        if ($to_date != '') {
            $query->where('created_at', '<=', gmdate('Y-m-d H:i:s', strtotime($to_date . ' 23:59:59')));
        }



         // -------------- Table ordering ------------
         $disableSortingColumnsIndex = [0, 1, 5];
         $tableColumnsIndexMaping = array(
             // 0-> row number,
             // 1-> actions
             2 => 'created_at',
             3 => 'order_id',
             4 => 'reference_number',
             5 => 'currency',
             6 => 'total_without_tax',
             7 => 'disraption',
             8 => 'artist_commission',
             9 => 'neworer_commission',
             10 => 'gateway',
             11 => 'tax',
             12 => 'total_with_tax',
         );

         // ready the sorting name
         $sort_name = '';
         $sort_order = '';

         // if the request has sort_index and sort_order then set the values
         if ($request->has('sort_index') && $request->has('sort_order')) {
             $sort_name = array_key_exists($request->sort_index, $tableColumnsIndexMaping) ? $tableColumnsIndexMaping[$request->sort_index] : '';
             $sort_order = $request->sort_order;
         }



         if ($sort_name && $sort_order) {

             // ______ If sort name is found then add the select to the query so we can order by the column ____

             switch ($sort_name) {

                 default:
                     // order the queries which can be directly order
                     $query->orderBy($sort_name, $sort_order);
                     break;
             }


             // _________________________________________________________________________________________________

         } else {
            $query->orderBy("id", "desc");
         }


         // ------------------------------------------


        if ($export) {
            return $this->exceltaxReporting($query);
        }


        //dd($query->toSql(), $query->getBindings());

        $bookings = $query->paginate(10);



        $viewType = "vendor";



        // Pass the data to the view
        return view('admin.veondor_booking.tax_report', compact('page_heading', 'type', 'user_id', 'bookings', 'editstatus', 'viewType', 'categories', 'disableSortingColumnsIndex'));
    }
    private function exceltaxReporting($queryDb)
    {

        $list = $queryDb->get();
        $rows = array();
        $i = 1;

        foreach ($list as $key => $val) {

            $haveOrders = $val->bookingOrder ? true : false;

            $rows[$key]['i'] = $i;
            $rows[$key]['booking_date_time'] = "";

            // Loop through the booking dates and append the date and time with line break
            foreach ($val->dates as $key2 => $val2) {
                $rows[$key]['booking_date_time'] .= web_date_in_timezone($val2->date, 'd-M-Y') . ' ' . date('h:i A', strtotime($val2->start_time)) . ' - ' . date('h:i A', strtotime($val2->end_time)) . "\n";
            }
            $rows[$key]['order_id'] = $val->order_id;
            $rows[$key]['total'] = $val->total;
            $rows[$key]['disruption_fee'] = number_format($val->disraption, 2, '.', '');
            $rows[$key]['artist_commission'] = number_format($val->artist_commission, 2, '.', '');
            $rows[$key]['new_order_commission'] = number_format($val->neworer_commission, 2, '.', '');
            $rows[$key]['gateway_fees'] = number_format($val->gateway, 2, '.', '');
            $rows[$key]['taxes_and_charges'] = $val->tax . " AED";
            $rows[$key]['net_total'] = $val->total_with_tax . " AED";
            $i++;
        }

        $headings = [
            "#",
            "Booking Date & Time",
            "Order ID",
            "Amount",
            "Disruption Fee",
            "Artist commission",
            "New oder commission",
            "Gateway Fees",
            "Total VAT",
            "Total with Tax",
        ];


        // Generate filename
        $file_name = 'tax_report_' . date('d_m_Y_h_i_s') . '.xlsx';


        return ExcelExporter::exportToExcel($headings, $rows, $file_name);
    }
}
