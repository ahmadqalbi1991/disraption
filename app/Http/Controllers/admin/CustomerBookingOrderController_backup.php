<?php

namespace App\Http\Controllers\admin;

use App\Exports\ExcelExporter;
use App\Http\Controllers\Admin\YatchBookingController;
use App\Models\Vendor\Yatch;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Vendor;
use App\Models\BookingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\CountryModel;
use App\Models\Facility;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor\PackageOrdAddon;
use App\Models\Vendor\PackageOrder;
use App\Models\Vendor\PackageOrdProd;
use App\Models\Vendor\ProductCategory;
use App\Models\Vendor\VendorBooking;
use App\Models\Vendor\VendorBookingDate;
use App\Models\Vendor\VendorPackage;
use App\Models\Vendor\YachtOrder;
use App\Models\YachtType;
use PhpParser\Node\Stmt\TryCatch;

class CustomerBookingOrderControllerBackuo extends Controller
{
    /**
     * Display a listing of the resource.
     * @param {"admin" | "vendor"}  $type
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type, $user_id)
    {
        $reporting = $request->reporting ?? null;


        // If it's not a reporting page then check the permission
        if (!$reporting && !get_user_permission('customers_booking_order', 'r')) {
            return redirect()->route('admin.restricted_page');
        } else if ($reporting && !get_user_permission('reporting_customers_booking_order', 'r')) {
            return redirect()->route('admin.restricted_page');
        }


        $order_id = $request->order_id ?? null;
        $refrence_no = $request->booking_refrence_no ?? null;
        $artist_name = $request->artist_name ?? null;
        $customer_name = $request->customer_name ?? null;
        $status = $request->status ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $export = $request->export ?? null;

        $page_heading = "Booking Orders" . ($reporting ? " Report" : "");



        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route('admin.restricted_page');
        }

        // Get the bookings orders by the user id
        $query = BookingOrder::with(['customer', 'vendor', 'booking.dates'])->orderBy("id", "desc");


        if ($user_id !== "all") {
            $query->where('customer_id', $user_id);
        }

        // If the status is not null then search the status
        if ($status) {
            $query->where('status', $status);
        }

        // If the order_id is not null then search the order_id
        if ($order_id) {
            $query->whereRaw('LOWER(order_id) like ?', ['%' . strtolower($order_id) . '%']);
        }

        // If the customer name is not null then search the customer name
        if ($customer_name) {
            $query->whereHas('customer', function ($q) use ($customer_name) {
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($customer_name) . '%']);
            });
        }

        if ($refrence_no) {
            $query->whereRaw('LOWER(reference_number) like ?', ['%' . strtolower($refrence_no) . '%']);
        }

        // If artistname lowercase for search
        if ($artist_name) {
            $query->whereHas('vendor', function ($q) use ($artist_name) {
                $q->whereRaw('LOWER(name) like ?', ['%' . strtolower($artist_name) . '%']);
            });
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


        // Pass the data to the view
        return view('admin.customers_booking_orders.list', compact('page_heading', 'type', 'user_id', 'bookings'));
    }



    private function vendorCustomerBookingCreate($customer_user_id)
    {

        $page_heading = "Booking Order";
        $id = "";

        // If the current user is not the vendor then return restricted page
        if (Auth::id() && Auth::user()->user_type_id !== 3) {
            return redirect()->route('vendor.restricted_page');
        }

        // Get all booking reference as array whose order is not yet created
        $bookingReferences =  VendorBooking::where('user_id', Auth::id())->whereDoesntHave('bookingOrder')->pluck('reference_number', 'reference_number')->toArray();


        // Get booking orders status
        $orderStatus = BookingOrder::$orderStatus;

        // Get all countries
        $countries = CountryModel::orderBy('name', 'asc')->get();


        return view('vendor.customers_booking_orders.create', compact('page_heading', 'id', 'bookingReferences', 'orderStatus', 'countries'));
    }



    public function create($type, $customer_user_id)
    {


        // If type is vendor then call the vendorCustomerBookingCreate method
        if ($type === 'vendor') {
            return $this->vendorCustomerBookingCreate($customer_user_id);
        }


        if (!get_user_permission('customers_booking_order', 'c')) {
            return redirect()->route('admin.restricted_page');
        }


        //if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($customer_user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route('admin.restricted_page');
        }


        $page_heading = "Booking Order";
        $id = "";
        $user_id = $customer_user_id;

        // Get booking orders status
        $orderStatus = BookingOrder::$orderStatus;

        // Get all customers users
        $customers = User::where('user_type_id', 2)->get();

  


        return view('admin.customers_booking_orders.create', compact('page_heading', 'type', 'user_id', 'customer_user_id', 'id', 'orderStatus', 'customers'));
    }


    /** */
    public function getUserByEmailOrPhone(Request $request)
    {

        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:email,phone',
        ]);

        // If the validation fails then return the error response
        if ($validator->fails()) {
            return $returnResponse("0", "Validation error occured", $validator->messages());
        }

        // If type is email then require email else require phone
        if ($request->type == 'email') {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'dialcode' => 'required',
            ]);
        }

        // If the validation fails then return the error response
        if ($validator->fails()) {
            return $returnResponse("0", "Validation error occured", $validator->messages());
        }


        // If the type is email then search the user by email
        if ($request->type == 'email') {
            $user = User::where('email', $request->email)->where('user_type_id', 2)->select('id', 'name', 'email', 'phone', 'dial_code')->first();
        } else {
            // If the type is phone then search the user by phone
            $user = User::where('phone', $request->phone)->where('user_type_id', 2)->where('dial_code', $request->dialcode)->select('id', 'name', 'email', 'phone', 'dial_code')->first();
        }

        // If the user is not found then return the error response
        if (!$user) {
            return $returnResponse("0", "User not found");
        }

        // Return the success response
        return $returnResponse("1", "User found", [], $user);
    }


    public function view($type, $customer_user_id, $order_id)
    {
        if (!get_user_permission('customers_booking_order', 'r')) {
            return redirect()->route('admin.restricted_page');
        }


        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($customer_user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Booking Order View";
        $user_id = $customer_user_id;
        $id = $order_id;


        // Get the booking order by the user id and the order id
        $booking = BookingOrder::with(['customer', 'vendor', 'booking', 'transactions'])->where('customer_id', $customer_user_id)->where('id', $order_id)->first();

        // If the booking order is not found then redirect to the restricted page
        if (!$booking) {
            return redirect()->route('admin.restricted_page');
        }


        return view('admin.customers_booking_orders.view', compact('page_heading', 'type', 'user_id', 'customer_user_id', 'id', 'booking'));
    }


    public function edit($type, $vendor_user_id, $id)
    {
        if (!get_user_permission('customers_booking_order', 'u')) {
            return redirect()->route('admin.restricted_page');
        }


        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($vendor_user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route('admin.restricted_page');
        }


        // Get the booking by the user id and the booking id
        $booking = VendorBooking::with('dates')->where('user_id', $vendor_user_id)->where('id', $id)->first();

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

        // looop through the booking dates and convert the time to 12-hour format
        foreach ($booking_dates as $key => $value) {
            $booking_dates[$key]['start_time'] = date('h:i A', strtotime($value['start_time']));
            $booking_dates[$key]['end_time'] = date('h:i A', strtotime($value['end_time']));
        }


        return view('admin.customers_booking_orders.create', compact('page_heading', 'type', 'user_id', 'vendor_user_id', 'id', 'title', 'reference_number', 'total', 'advance', 'order_id', 'booking_dates'));
    }




    public function store(Request $request)
    {

        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'refrence_no' => 'required',
            // required payment with only advance and full value
            'payment' => 'required|in:advance,full',
        ]);

        $user_id = $request->user_id;


        // If the validation fails then return the error response
        if ($validator->fails()) {
            return $returnResponse("0", "Validation error occured", $validator->messages());
        }


        // Generate order number of 7 digits with prefix D
        $order_number = "D" . rand(1000000, 9999999);
        $transaction_id = "D" . rand(1000000, 9999999);
        $vendor_panel_redirect_url = null;

        $isVendorPanelCall = ($user_id == "custom_id") ? true : false;



        // ---------- If the user_id is equal to the custom_id then it means it's vendor side call and from vendor panel we only allow the customer to be find by the email or phone
        if ($isVendorPanelCall && !$request->id) {

            $validator = Validator::make($request->all(), [
                'option' => 'required|in:email,phone',
            ]);

            // If the validation fails then return the error response
            if ($validator->fails()) {
                return $returnResponse("0", "Validation error occured", $validator->messages());
            }

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

            // If the option is email then search the customer by the email by lowercase the db and request email
            if ($request->option == 'email') {
                // Lowercase the email and search the customer by the email
                $customer = User::whereRaw('LOWER(email) = ?', [strtolower($request->custm_email)])
                    ->where('user_type_id', 2)
                    ->first();

                // If the customer is not found then return the error response
                if (!$customer) {
                    return $returnResponse("0", "Customer not found");
                }

                // Set the customer id to the customer id
                $user_id = $customer->id;

            } else {

                // If the option is phone then search the customer by the phone
                $customer = User::where('phone', $request->custm_phone)->where('dial_code', $request->custm_dialcode)->where('user_type_id', 2)->first();
            
                // If the customer is not found then return the error response
                if (!$customer) {
                    return $returnResponse("0", "Customer not found");
                }

                // Set the customer id to the customer id
                $user_id = $customer->id;
            
            }

        }

        // --------------------

        // Get the booking by the refrence_no
        $bookingQuery = VendorBooking::with('bookingOrder')->where('reference_number', $request->refrence_no);

        // If $isVendorPanelCall add the current vendor user id to the query
        if ($isVendorPanelCall) {
            $bookingQuery->where('user_id', Auth::id());
        }

        $booking = $bookingQuery->first();


        // If the booking is not found then return the error response
        if (!$booking) {
            return $returnResponse("0", "Booking not found");
        }



        // If the bookingOrder exist then return error saying the booking already created
        if ($booking->bookingOrder) {
            return $returnResponse("0", "Order already exist for this booking");
        }


        // Start a database transaction
        DB::beginTransaction();


        try {

            // if request payment is advance then set the status to confirmed else set it to completed
            $status = $request->payment == 'advance' ? 'confirmed' : 'completed';

            // if request payment is advance then set the advance to advance else set to total
            $transactionAmount = $request->payment == 'advance' ? $booking->advance : $booking->total;

            // transaction type if request payment is advance then set the type to booking_advance else set to booking_full
            $transactionType = $request->payment == 'advance' ? Transaction::$type_Advance : Transaction::$type_Full;

            // Create a new booking order
            $bookingOrder = new BookingOrder();
            $bookingOrder->customer_id = $user_id;
            $bookingOrder->vendor_id = $booking->user_id;
            $bookingOrder->booking_id = $booking->id;
            $bookingOrder->reference_number = $request->refrence_no;
            $bookingOrder->order_id = $order_number;
            $bookingOrder->total_paid = $transactionAmount;
            $bookingOrder->tax = 0;
            $bookingOrder->discount = 0;
            // Get status from the booking order static values confirmed
            $bookingOrder->status = $status;
            $bookingOrder->save();


            // Add the transaction
            $transaction = new Transaction();
            $transaction->customer_id = $user_id;
            $transaction->vendor_id = $booking->user_id;
            $transaction->order_id = $bookingOrder->id;
            $transaction->transaction_id = $transaction_id;
            $transaction->status = Transaction::$payment_status_Success;
            $transaction->amount = $transactionAmount;
            $transaction->type = $transactionType;
            $transaction->payment_method = Transaction::$payment_method_Wallet;
            $transaction->save();

            $vendor_panel_redirect_url = route('vendor.artist-booking.edit', ['type'=>'vendor','user_id'=>Auth::id(), 'id'=>$booking->id]);


            // Commit the transaction
            DB::commit();
        } catch (\Exception $e) {

            // Rollback the transaction
            DB::rollBack();
            return $returnResponse("0", "An error occured while creating the order");
        }


        // Return the success message
        return $returnResponse("1", "Order created successfully", [], ['order_id'=>$order_number, 'vendor_panel_redirect_url'=> $vendor_panel_redirect_url]);
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
        $order = BookingOrder::where('id', $request->orderId)
            ->where('vendor_id', $user_id)
            ->first();

        if ($order) {
            $order->status = $request->status;
            $order->save();
            return $returnResponse("1", "Status updated successfully!", []);
        } else {
            return $returnResponse("0", "Order not found!", []);
        }
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



    private function excelReporting($queryDb)
    {

        $list = $queryDb->get();
        $rows = array();
        $i = 1;

        foreach ($list as $key => $val) {


            $rows[$key]['i'] = $i;
            $rows[$key]['refrence_no'] = $val->reference_number;
            $rows[$key]['total'] = $val->booking->total;
            $rows[$key]['advance'] = $val->booking->advance;
            $rows[$key]['name'] = $val->vendor->name;
            $rows[$key]['email'] = $val->vendor->email;
            $rows[$key]['customer_name'] = $val->customer->name;
            $rows[$key]['customer_email'] = $val->customer->email;
            $rows[$key]['order_id'] = $val->order_id;
            $rows[$key]['amount_paid'] = $val->total_paid;
            $rows[$key]['status'] = ucfirst($val->status);
            $rows[$key]['booking_date_time'] = '';

            // Loop through the booking dates and append the date and time with line break
            foreach ($val->booking->dates as $key2 => $val2) {
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
            "Amount Paid",
            "Order Status",
            "Booking Date & Time",
            "Created Date",
        ];


        // Generate filename
        $file_name = 'bookings_orders_' . date('d_m_Y_h_i_s') . '.xlsx';


        return ExcelExporter::exportToExcel($headings, $rows, $file_name);
    }
}
