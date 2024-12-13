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

class CustomerTransactionController extends Controller
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
        if (!$reporting && !get_user_permission('customers_transactions', 'r')) {
            return redirect()->route('admin.restricted_page');
        } else if ($reporting && !get_user_permission('reporting_customers_transactions', 'r')) {
            return redirect()->route('admin.restricted_page');
        }


        $refrence_no = $request->refrence_no ?? null;
        $artist_name = $request->artist_name ?? null;
        $reporting = $request->reporting ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $export = $request->export ?? null;

        $page_heading = "Transactions" . ($reporting ? " Report" : "");



        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route('admin.restricted_page');
        }

        // Get the bookings orders by the user id
        $query = BookingOrder::with(['customer', 'vendor.vendor_details', 'booking'])->orderBy("id", "desc");



        if ($user_id !== "all") {
            $query->where('customer_id', $user_id);
        }

        if ($refrence_no) {
            $query->where('reference_number', 'like', '%' . $refrence_no . '%');
        }

        // If artistname lowercase for search
        if ($artist_name) {
            $query->whereHas('user', function ($q) use ($artist_name) {
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
        return view('admin.customers_transactions.list', compact('page_heading', 'type', 'user_id', 'bookings'));
    }



    // Update the order status
    public function change_status(Request $request, $type, $user_id)
    {
        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };

        $validator = Validator::make($request->all(), [
            'transactionId' => 'required',
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


        // find the transaction by the id and the vendor id
        $transaction = Transaction::where('id', $request->transactionId)->where('vendor_id', $user_id)->first();

        // If the transaction is found then save else return the error response
        if ($transaction) {
            $transaction->status = $request->status;
            $transaction->save();
            return $returnResponse("1", "Status updated successfully", [], $transaction);
       
        } else {
            return $returnResponse("0", "Transaction not found!", []);
        }
    }


    private function excelReporting($queryDb)
    {

        $list = $queryDb->get();
        $rows = array();
        $i = 1;
        foreach ($list as $key => $val) {

            $rows[$key]['i'] = $i;
            $rows[$key]['refrence_no'] = $val->reference_number;
            $rows[$key]['total'] = $val->total;
            $rows[$key]['advance'] = $val->advance;
            $rows[$key]['name'] = $val->user->name;
            $rows[$key]['email'] = $val->user->email;
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
            "Booking Date & Time",
            "Created Date",
        ];


        // Generate filename
        $file_name = 'artist_bookings_' . date('d_m_Y_h_i_s') . '.xlsx';


        return ExcelExporter::exportToExcel($headings, $rows, $file_name);
    }
}
