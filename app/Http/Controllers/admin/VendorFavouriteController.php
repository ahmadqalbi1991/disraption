<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExcelExporter;
use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\VendorRating;

class VendorFavouriteController extends Controller
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
        $ratingsQuery = VendorRating::orderBy('id', 'desc');

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
            $ratingsQuery->whereDate('created_at', '>=', $from_date);
        }

        // If the to date is provided then filter the ratings by to date
        if ($to_date) {
            $ratingsQuery->whereDate('created_at', '<=', $to_date);
        }


        if ($export) {
            return $this->excelReporting($ratingsQuery);
        }

        // Get the ratings
        $ratings = $ratingsQuery->paginate(10);


        return view('admin.vendors.ratings.list', compact('page_heading', 'ratings'));
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


    public function apiGetFavourites(Request $request)
    {


        $limit = $request->limit ?? 10;

        // Get the current user
        $customer = Auth::user();

        // Get the favourite vendors
        $favourites = Favourite::with('vendor.vendor_details.category')->where('customer_id', $customer->id)->paginate($limit);

        // clean
        $favourites = cleanPaginationResultArray($favourites->toArray());
        $favourites = convert_all_elements_to_string($favourites);

        // Success response
        return response()->success('Favourites', $favourites);

    }


    public function apiAddFavourite(Request $request)
    {

        // Validate media variable in the request as array
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->error($validator->errors()->first(), $validator->errors());
        }

        // Get the vendor by the id
        $vendor = User::where("user_type_id", 3)->find($request->vendor_id);

        // If the vendor is not found then return error
        if (!$vendor) {
            return response()->error('Artist not found');
        }

        // Get the current user
        $customer = Auth::user();

        // Check if the vendor is already in the favourite list
        $favourite = Favourite::where('vendor_id', $vendor->id)->where('customer_id', $customer->id)->first();

        // If the vendor is already in the favourite list then return error
        if ($favourite) {
            return response()->error('Artist already in favourite list');
        }

        // Create the favourite object
        $favourite = new Favourite();
        $favourite->vendor_id = $vendor->id;
        $favourite->customer_id = $customer->id;
        $favourite->save();

        // Success response
        return response()->success('Artist added to favourite list');
    }


    public function apiRemoveFavourite(Request $request)
    {

        // Validate media variable in the request as array
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {
            return response()->error($validator->errors()->first(), $validator->errors());
        }

        // Get the vendor by the id
        $vendor = User::where("user_type_id", 3)->find($request->vendor_id);

        // If the vendor is not found then return error
        if (!$vendor) {
            return response()->error('Artist not found');
        }

        // Get the current user
        $customer = Auth::user();

        // Check if the vendor is already in the favourite list
        $favourite = Favourite::where('vendor_id', $vendor->id)->where('customer_id', $customer->id)->first();

        // If the vendor is not in the favourite list then return error
        if (!$favourite) {
            return response()->error('Artist not in favourite list');
        }

        // Delete the favourite object
        $favourite->delete();

        // Success response
        return response()->success('Artist removed from favourite list');
    }
}
