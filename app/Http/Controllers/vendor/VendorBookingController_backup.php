<?php

namespace App\Http\Controllers\vendor;

use App\Exports\ExcelExporter;
use App\Http\Controllers\Admin\YatchBookingController;
use App\Models\Vendor\Yatch;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Vendor;
use App\Models\BookingOrder;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\CountryModel;
use App\Models\CustomerUserDetail;
use App\Models\Facility;
use App\Models\TempTransaction;
use App\Models\Transaction;
use App\Models\User;
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

        $page_heading = "Booking" . ($reporting ? " Report" : "");

        // Ger vendorId variable value from  request query
        $vendorId = request()->query('vendorid');
        $editstatus = request()->query('editstatus');

        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route($restrictedPageRoute);
        }



        // Get the bookings by the user i
        $query = VendorBooking::with(['user', 'dates', 'customer'])->orderBy("id", "desc");

        if ($user_id !== "all") {
            $query->where('user_id', $user_id);
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

        if ($export) {
            return $this->excelReporting($query);
        }

        $bookings = $query->paginate(10);


        $viewType = "vendor";

        // Pass the data to the view
        return view('admin.veondor_booking.list', compact('page_heading', 'type', 'user_id', 'bookings', 'editstatus', 'viewType'));
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



        $page_heading = "Booking";
        $id = "";
        $user_id = $vendor_user_id;

        $date = "";
        $start_time = "";
        $end_time = "";
        $title = "";

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

            $query->select('user_id', 'hourly_rate', 'deposit_amount');
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


        return view('admin.veondor_booking.create', compact('page_heading', 'type', 'user_id', 'vendor_user_id', 'id', 'date', 'start_time', 'end_time', 'title', 'reference_number', 'total', 'advance', 'order_id', 'booking_dates', 'booking', 'vendors', 'customers', 'vendors_array', 'medias', 'maxImgsAllowed', 'countries'));
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
        $booking = VendorBooking::with(['dates', 'transactions', 'customer'])->where('user_id', $vendor_user_id)->where('id', $id)->first();

        $page_heading = "Booking";
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

        $booking_dates = $booking->dates->toArray();

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



        return view('admin.veondor_booking.create', compact('page_heading', 'type', 'user_id', 'vendor_user_id', 'id', 'title', 'reference_number', 'total', 'advance', 'order_id', 'booking', 'booking_dates', 'customers', 'vendors', 'vendors_array', 'medias', 'maxImgsAllowed', 'countries'));
    }




    static function CalculateDurationHours($start_time, $end_time)
    {

        $start_time = strtotime($start_time);
        $end_time = strtotime($end_time);
        $duration_hours = ceil(($end_time - $start_time) / 3600);
        return (int)$duration_hours;
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
            'newMedias.*' => 'image|mimes:jpeg,png,jpg|max:5048',

        ]);

        try {

            $user_id = $request->user_id; // vendor id

            $customer_id = $request->customer_id; // customer id

            // -------- If the user is vendor then on vendor panel we only allow the customer to be added by the email or the phone so validate etc and set the custome id -----

            if (Auth::user()->user_type_id == 3) {

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
                $Order->reference_number = VendorBooking::generateUniqueReferenceNumber();
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

            $Order->total = 0;
            $Order->advance = $Order->user->vendor_details->deposit_amount;
            $Order->hourly_rate = $Order->user->vendor_details->hourly_rate;


            // Save the order
            $Order->save();


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
                        'date' => $value['date'],
                        'start_time' => VendorBookingController::convertTo24HourFormat($value['start_time']),
                        'end_time' => VendorBookingController::convertTo24HourFormat($value['end_time']),
                    ];
                } else {
                    // If the key id does not contain new- then it means it's an updated booking date
                    $updatedBookingDates[] = [
                        'id' => $key,
                        'booking_id' => $Order->id,
                        'date' => $value['date'],
                        'start_time' => VendorBookingController::convertTo24HourFormat($value['start_time']),
                        'end_time' => VendorBookingController::convertTo24HourFormat($value['end_time']),
                    ];
                }
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
            $this->fireBaseNotificationOnStatusUpdate($order->customer, $order->id);

            return $returnResponse("1", "Status updated successfully!", []);
        } else {
            return $returnResponse("0", "Order not found!", []);
        }
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

 



    public function apiBookingPay(Request $request)
    {

        $data = [];
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
            $customer->customerUserDetail->wallet_balance -= $booking->total_with_tax;
            $customer->customerUserDetail->save();


            // _______ Booking Status and update total_paid _______

            // Status based on current stage type.
            $status = $crntStageData["type"] == "advance" ? "pending" : "completed";

            // As it's wallet payment which is success, so add the total amount to the total paid
            $booking->total_paid += $oustandingAmount;

            $booking->last_payment_method = "wallet";


            // Update the booking status to paid
            $booking->status = $status;
            $booking->save();

            // Firebase notify
            $this->fireBaseNotificationOnStatusUpdate($booking->customer, $booking->id);

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
            $status = $crntStageData["type"] == "advance" ? "pending" : "completed";


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
                case 'apple_pay':
                    $transactionData['payment_method'] = Transaction::$payment_method_StripeApple;
                    break;
                case 'google_pay':
                    $transactionData['payment_method'] = Transaction::$payment_method_StripeGoogle;
                    break;
                case 'card':
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
            $this->fireBaseNotificationOnStatusUpdate($booking->customer, $booking->id);

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

            $bookings = VendorBooking::where('customer_id', $user->id)->where('status', '!=', 'created')->with(
                [
                    'vendor' => function ($query) {
                        $query->select(['id', 'name', 'user_image']);
                    },
                    'vendor.vendor_details' => function ($query) {
                        $query->select(['user_id', 'total_rating']);
                    },
                ]
            )->orderBy("id", "desc")->paginate($limit);

            if ($bookings->isEmpty()) {
                return response()->error("No Bookings found", []);
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
                    'dates',
                    'medias',
                    'review.customer' => function ($query) {
                        $query->select(['id', 'name']);
                    },
                    'vendor' => function ($query) {
                        $query->select(['id', 'name', 'user_image']);
                    },
                    'vendor.vendor_details' => function ($query) {
                        $query->select(['user_id', 'total_rating', 'r_policy']);
                    },
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



            $booking = convert_all_elements_to_string($booking->toArray());

            $booking["wallet_balance"] = $balance;


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



    public function fireBaseNotificationOnStatusUpdate($customer_obj, $booking_id)
    {

      return  prepare_notification($customer_obj, "Booking status updated", "Your booking status has been updated", "booking", $booking_id);
        

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
            $rows[$key]['booking_date_time'] = '';

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
            "Booking Date & Time",
            "Created Date",
        ];


        // Generate filename
        $file_name = 'artist_bookings_' . date('d_m_Y_h_i_s') . '.xlsx';


        return ExcelExporter::exportToExcel($headings, $rows, $file_name);
    }
}
