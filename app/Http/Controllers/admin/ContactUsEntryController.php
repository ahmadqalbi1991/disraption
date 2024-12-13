<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ExcelExporter;
use App\Http\Controllers\Controller;
use App\Models\ContactUsEntry;
use App\Models\Favourite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\VendorRating;

class ContactUsEntryController extends Controller
{



    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        // If it's not a reporting page then check the permission
        if (!get_user_permission('contact_us_entries', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Contact Us";


        // Get all the entries from the ContactUsEntry model with message with partial message
        $query = ContactUsEntry::query();


        // -------------- Table ordering ------------
        $disableSortingColumnsIndex = [0, 1];
        $tableColumnsIndexMaping = array(
            // 0-> row number,
            // 1-> actions
            2 => 'name',
            3 => 'email',
            4 => 'phone',
            5 => 'message',
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

                case 'phone':
                    $query->orderBy('dial_code', $sort_order)
                        ->orderBy('phone', $sort_order);
                    break;

                default:
                    // order the queries which can be directly order
                    $query->orderBy($sort_name, $sort_order);
                    break;
            }


            // _________________________________________________________________________________________________

        } else {
            $query->orderBy('id', 'desc');
        }


        // ------------------------------------------


        $entries = $query->paginate(10);

        return view('admin.contact_us.list', compact('page_heading', 'entries', 'disableSortingColumnsIndex'));
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


    public function show($contact_us)
    {
        if (!get_user_permission('contact_us_entries', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Contact Us";

        // Get the contact by the id
        $entry = ContactUsEntry::find($contact_us);

        if (!$entry) {
            return redirect()->route('admin.restricted_page');
        }


        return view('admin.contact_us.view', compact('page_heading', 'entry'));
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


    public function apiAddEntry(Request $request)
    {
        // validator for the name, email, dial_code, phone, message
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email',
            'dial_code' => 'numeric',
            'phone' => 'numeric',
            'message' => 'required',
        ]);

        // If validation fails, return an error response
        if ($validator->fails()) {

            return response()->error($validator->errors()->first(), $validator->errors());
        }

        // Create the contact us entry
        $contactUsEntry = new ContactUsEntry();
        $contactUsEntry->customer_id = Auth::id();
        $contactUsEntry->name = $request->name;

        if ($request->email) {
            $contactUsEntry->email = $request->email;
        }

        if ($request->dial_code) {
            $contactUsEntry->dial_code = $request->dial_code;
        }

        if ($request->phone) {
            $contactUsEntry->phone = $request->phone;
        }

        $contactUsEntry->message = $request->message;

        $contactUsEntry->save();

        return response()->success('Contact us entry added successfully');
    }
}
