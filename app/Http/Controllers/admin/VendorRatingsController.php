<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExcelExporter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\VendorRating;
use Carbon\Carbon;

class VendorRatingsController extends Controller
{



    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $reporting = $request->reporting ?? null;


        // If it's not a reporting page then check the permission
        if (!$reporting && !get_user_permission('vendor_ratings', 'r')) {
            return redirect()->route('admin.restricted_page');
        } else if ($reporting && !get_user_permission('reporting_vendors_rating', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Artist Ratings"  . ($reporting ? " Report" : "");

        // Get the query parameters
        $customer_email = $request->customer_email ?? null;
        $vendor_email = $request->vendor_email ?? null;
        $rating = $request->rating ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $export = $request->export ?? null;



        // Get all the ratings sort by id desc
        $ratingsQuery = VendorRating::query(); // Initialize the query builder

        // If the customer email is provided then filter the ratings by customer email
        if ($customer_email) {
            $ratingsQuery->whereHas('customer', function ($query) use ($customer_email) {

                // Lower case the email and search in the database using whereRaw
                $query->whereRaw('LOWER(email) LIKE ?', ['%' . strtolower($customer_email) . '%']);
            });
        }

        // If the vendor email is provided then filter the ratings by vendor email
        if ($vendor_email) {

            $ratingsQuery->whereHas('vendor', function ($query) use ($vendor_email) {

                // Lower case the email and search in the database using whereRaw
                $query->whereRaw('LOWER(email) LIKE ?', ['%' . strtolower($vendor_email) . '%']);
            });
        }

        // If the rating is provided then filter the ratings by rating
        if ($rating) {
            $ratingsQuery->where('rating', $rating);
        }

        // If the from date is provided then filter the ratings by from date
        if ($from_date) {
            $from_date = Carbon::createFromFormat('d-m-Y', $from_date)->startOfDay()->format('Y-m-d H:i:s');
            $ratingsQuery->whereDate('created_at', '>=', $from_date);
        }

        // If the to date is provided then filter the ratings by to date
        if ($to_date) {
            $to_date = Carbon::createFromFormat('d-m-Y', $to_date)->startOfDay()->format('Y-m-d H:i:s');
            $ratingsQuery->whereDate('created_at', '<=', $to_date);
        }



        // -------------- Table ordering ------------
        $disableSortingColumnsIndex = [0, 1];
        $tableColumnsIndexMaping = array(
            // 0-> row number,
            // 1-> actions
            2 => 'customer_details',
            3 => 'artist_details',
            4 => 'rating',
            5 => 'review',
            6 => 'created_at',
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
                case 'customer_details':

                    // Join related tables for sorting by type
                    $ratingsQuery->leftJoin('users', 'users.id', '=', 'vendor_ratings.user_id')
                        ->select('vendor_ratings.*', 'users.name as customer_name');

                    $ratingsQuery->orderBy('customer_name', $sort_order);

                    break;
                case 'artist_details':

                    // Join related tables for sorting by type
                    $ratingsQuery->leftJoin('users', 'users.id', '=', 'vendor_ratings.vendor_id')
                        ->select('vendor_ratings.*', 'users.name as vendor_name');

                    $ratingsQuery->orderBy('vendor_name', $sort_order);

                case 'review':

                    $ratingsQuery->orderBy(DB::raw('LOWER(SUBSTRING(review, 1, 1))'), $sort_order);

                    break;
                default:
                    // order the queries which can be directly order
                    $ratingsQuery->orderBy($sort_name, $sort_order);
                    break;
            }


            // _________________________________________________________________________________________________

        } else {
            $ratingsQuery->orderBy('id', 'desc');
        }


        // ------------------------------------------


        if ($export) {
            return $this->excelReporting($ratingsQuery);
        }

        // Get the ratings
        $ratings = $ratingsQuery->paginate(10);


        return view('admin.vendors.ratings.list', compact('page_heading', 'ratings', 'disableSortingColumnsIndex'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!get_user_permission('vendor_ratings', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Artist Ratings";

        $stars = [1, 2, 3, 4, 5];
        $star = 0;
        $review = "";

        $customers = [];
        $vendors = [];

        // Get all customers
        $customers = User::where('user_type_id', 2)->get();

        // Get all vendors
        $vendors = User::where('user_type_id', 3)->get();

        $customer_id = "";
        $vendor_id = "";
        $rating_id = "";


        return view('admin.vendors.ratings.create', compact('page_heading', 'stars', 'star', 'review', 'customers', 'vendors', 'customer_id', 'vendor_id', 'rating_id'));
    }


    public function edit($ratingId)
    {
        if (!get_user_permission('vendor_ratings', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Artist Ratings";

        // Get the rating by the id
        $rating = VendorRating::find($ratingId);

        if (!$rating) {
            return redirect()->route('admin.vendor_ratings.index');
        }

        $stars = [1, 2, 3, 4, 5];
        $star = $rating->rating;
        $review = $rating->review;

        $customers = [];
        $vendors = [];

        // Get all customers
        $customers = User::where('user_type_id', 2)->get();

        // Get all vendors
        $vendors = User::where('user_type_id', 3)->get();

        $customer_id = $rating->user_id;
        $vendor_id = $rating->vendor_id;
        $rating_id = $rating->id;


        return view('admin.vendors.ratings.create', compact('page_heading', 'stars', 'star', 'review', 'customers', 'vendors', 'customer_id', 'vendor_id', 'rating_id'));
    }




    // create the store function
    public function store(Request $request)
    {



        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };


        // Validate media variable in the request as array
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'vendor_id' => 'required',
            'rating' => 'required',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return $returnResponse('0', 'Validation error', $validator->errors());
        }


        // If the current user is not admin
        if (Auth::user()->user_type_id !== 1) {
            return $returnResponse(0, 'Unauthorized access');
        }

        // Get the customer by the id and user_type_id 2
        $customer = User::where('id', $request->customer_id)->where('user_type_id', 2)->first();

        // Get the vendor by the id and user_type_id 3
        $vendor = User::where('id', $request->vendor_id)->where('user_type_id', 3)->first();

        // If the customer or vendor is not found then return error
        if (!$customer || !$vendor) {
            return $returnResponse('0', 'Customer or vendor not found');
        }

        // Ready the $rating model object if id is provided then get the rating by id else create new object
        $rating = null;

        $successMessage = "Rating added successfully";

        if ($request->id != "") {
            $rating = VendorRating::find($request->id);

            if (!$rating) {
                return $returnResponse('0', 'Rating not found');
            }

            $successMessage = "Rating updated successfully";
        } else {
            $rating = new VendorRating();
        }


        // if rating is not between 1 to 5 then return error
        if ((int)$request->rating < 1 || $request->rating > 5) {
            return $returnResponse('0', 'Rating must be between 1 to 5');
        }


        // Set the rating object properties
        $rating->user_id = $customer->id;
        $rating->vendor_id = $vendor->id;
        $rating->rating = $request->rating;
        $rating->review = $request->review;
        $rating->save();


        // Update the total rating of the using $vendor object
        $vendor->updateVendorTotalRatings();



        // Success response
        return $returnResponse('1', $successMessage);
    }


    // Delete the rating
    public function delete($ratingId)
    {
        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };

        // Get the rating by the id
        $rating = VendorRating::find($ratingId);

        // If the rating is not found then return error
        if (!$rating) {
            return $returnResponse('0', 'Rating not found');
        }

        // Get the vendor by the id
        $vendor = User::find($rating->vendor_id);

        // Delete the rating
        $rating->delete();

        // Update the total rating of the using $vendor object
        $vendor->updateVendorTotalRatings();

        // Success response
        return $returnResponse('1', 'Rating deleted successfully');
    }


    private function excelReporting($ratingsQuery)
    {

        $list = $ratingsQuery->get();
        $rows = array();
        $i = 1;
        foreach ($list as $key => $val) {

            $rows[$key]['i'] = $i;
            $rows[$key]['customer_name'] = $val->customer->name;
            $rows[$key]['customer_email'] = $val->customer->email;
            $rows[$key]['customer_phone'] = '+' . ($val->customer->dial_code != '') ? $val->customer->dial_code . ' ' . $val->customer->phone : '-';
            $rows[$key]['artist_name'] = $val->vendor->name;
            $rows[$key]['artist_email'] = $val->vendor->email;
            $rows[$key]['artist_phone'] = '+' . ($val->vendor->dial_code != '') ? $val->vendor->dial_code . ' ' . $val->vendor->phone : '-';
            $rows[$key]['star'] = $val->rating;
            $rows[$key]['review'] = $val->review;
            $rows[$key]['created_date'] = web_date_in_timezone($val->created_at, 'd-m-y h:i A');


            $i++;
        }

        $headings = [
            "#",
            "Customer Name",
            "Customer Email",
            "Customer Mobile",
            "Artist Name",
            "Artist Email",
            "Artist Mobile",
            "Star",
            "Review",
            "Created Date",
        ];


        // Generate filename
        $file_name = 'artist_ratings_' . date('d_m_Y_h_i_s') . '.xlsx';


        return ExcelExporter::exportToExcel($headings, $rows, $file_name);
    }
}