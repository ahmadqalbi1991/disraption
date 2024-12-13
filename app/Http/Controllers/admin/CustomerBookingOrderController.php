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

class CustomerBookingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param {"admin" | "vendor"}  $type
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type, $user_id)
    {

         // -------------- Table ordering ------------
         $disableSortingColumnsIndex = [0, 1];
         $tableColumnsIndexMaping = array(
            // 0-> row number,
            // 1-> actions
            2 => 'vendor_bookings.reference_number',
            3 => 'vendor_bookings.order_id',
            4 => 'vendor_bookings.status',
            5 => 'customer',
            7 => 'vendor_bookings.total_paid',
            8 => 'vendor_bookings.total_without_tax',
            9 => 'vendor_bookings.tax',
            10 => 'vendor_bookings.disraption',
            11 => 'vendor_bookings.artist_commission',
            12 => 'vendor_bookings.neworer_commission',
            13 => 'vendor_bookings.gateway',
            14 => 'vendor_bookings.total_with_tax',
            15 => 'vendor_bookings.created_at',
        );
         // ------------------------------------------

        $reporting = $request->reporting ?? null;
        $restrictedPageRoute = route_name_admin_vendor($type, 'restricted_page');

        // If it's not a reporting page then check the permission
        if (!$reporting && !get_user_permission('customers_booking_order', 'r')) {
            return redirect()->route('admin.restricted_page');
        } else if ($reporting && !get_user_permission('reporting_customers_booking_order', 'r')) {
            return redirect()->route('admin.restricted_page');
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



        // Get the bookings by the custome user_id
        $query = VendorBooking::with(['user', 'dates', 'customer'])->orderBy("id", "desc");

        if ($user_id !== "all") {
            $query->where('customer_id', $user_id);
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

       

        $bookings = $query->paginate(10);


        $viewType = "customer";

        // Pass the data to the view
        return view('admin.veondor_booking.list', compact('page_heading', 'type', 'user_id', 'bookings', 'editstatus', 'viewType', 'disableSortingColumnsIndex', 'tableColumnsIndexMaping'));
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
        $booking = VendorBooking::with(['vendor', 'dates', 'customer', 'transactions'])->orderBy("id", "desc")->where('customer_id', $customer_user_id)->where('id', $order_id)->first();

        // If the booking order is not found then redirect to the restricted page
        if (!$booking) {
            return redirect()->route('admin.restricted_page');
        }


        return view('admin.customers_booking_orders.view', compact('page_heading', 'type', 'user_id', 'customer_user_id', 'id', 'booking'));
    }


}
