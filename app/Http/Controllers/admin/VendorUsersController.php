<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CountryModel;
use App\Models\HearAboutUs;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Vendor\VendorUserDetail;
use App\Exports\ExcelExporter;
use App\Models\AppBanner;
use App\Models\Vendor\VendorPortfolio;
use App\Models\VendorRating;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\admin\PagesController;
use App\Http\Middleware\AuthenticateWithSanctum;
use App\Models\Vendor\VendorBooking;
use DateInterval;
use DatePeriod;
use DateTime;
use Carbon\Carbon;

class VendorUsersController extends Controller
{
    public function index(Request $request)
    {

        $reporting = $request->reporting ?? null;


        // If it's not a reporting page then check the permission
        if (!$reporting && !get_user_permission('vendors', 'r')) {
            return redirect()->route('admin.restricted_page');
        } else if ($reporting && !get_user_permission('reporting_vendors', 'r')) {
            return redirect()->route('admin.restricted_page');
        }

        // Get the query parameters
        $name = $request->name ?? null;
        $status = $request->status ?? null;
        $verified = $request->verified ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $export = $request->export ?? null;
        $category_id = $request->category_id ?? null;
        $type = $request->type ?? null;
        $gender = $request->gender ?? null;
        $username = $request->username ?? null;

        // Append "Reporting" if the $reporting is not null
        $page_heading = "Artists" . ($reporting ? " Reports" : "");

        // $users_db = DB::table('users')
        //     ->leftJoin('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
        //     ->where('users.user_type_id', '=', 3)
        //     ->select(
        //         'users.*',
        //         'vendor_user_details.*',
        //     )
        //     ->groupBy('users.id', 'vendor_user_details.id', 'vendor_user_details.user_id')
        //     ->orderBy('users.id', 'desc');

        $users_db = User::with(['vendor_details.category'])
            ->where('user_type_id', 3);

        // Apply conditions on the vendor_details relationship
        if ($category_id) {
            $users_db->whereHas('vendor_details', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }

        if ($type) {
            $users_db->whereHas('vendor_details', function ($query) use ($type) {
                $query->where('type', $type);
            });
        }

        if ($gender) {
            $users_db->whereHas('vendor_details', function ($query) use ($gender) {
                $query->where('gender', $gender);
            });
        }

        if ($username) {
            $users_db->whereHas('vendor_details', function ($query) use ($username) {
                $query->whereRaw('LOWER(vendor_user_details.username) LIKE ?', ['%' . strtolower($username) . '%']);
            });
        }

        // Apply other conditions
        if ($name) {
            $users_db->whereRaw('LOWER(users.name) LIKE ?', ['%' . strtolower($name) . '%']);
        }

        if ($status !== null) {
            $users_db->where('users.active', $status);
        }

        if ($verified !== null) {
            $users_db->where('users.verified', $verified);
        }

        if ($from_date != '') {
            $from_date=Carbon::createFromFormat('d-m-Y',  $from_date)->format('Y-m-d');
            $users_db->whereHas('vendor_details', function ($query) use ($from_date) {
                $query->whereDate('availability_from', '<=', $from_date);
            });
        }

        if ($to_date != '') {
            $to_date=Carbon::createFromFormat('d-m-Y',  $to_date)->format('Y-m-d');
            $users_db->whereHas('vendor_details', function ($query) use ($to_date) {
                $query->whereDate('availability_to', '>=', $to_date);
            });
        }

        // customerBookings relation count
        $users_db->withCount('customerBookings');


        $users_db->withSum('vendorBookings', 'total_hours');



        // -------------- Table ordering ------------
        $disableSortingColumnsIndex = [0, 1, 5];
        $tableColumnsIndexMaping = array(
            // 0-> row number,
            // 1-> actions
            2 => 'users.name',
            3 => 'category',
            4 => 'type',
            5 => 'sales_rating',
            6 => 'total_rating',
            7 => 'gender',
            8 => 'age',
            9 => 'working_dates',
            10 => 'users.active',
            11 => 'users.created_at',
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

                case 'users.name':

                    $users_db->orderByRaw('LOWER(LEFT(users.name, 1)) ' . $sort_order);

                    break;

                case 'category':

                    // Join related tables for sorting by category
                    $users_db->leftJoin('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
                    ->leftJoin('category', 'vendor_user_details.category_id', '=', 'category.id')
                    ->select(
                        'users.*',
                        DB::raw('SUBSTRING(LOWER(category.name), 1, 1) as category_name') // Get the first character of the category name, case-insensitive
                    );

                    $users_db->orderBy('category_name', $sort_order);
                    break;
                case 'type':
                    // Join related tables for sorting by type
                    $users_db->leftJoin('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
                        ->select('users.*', 'vendor_user_details.type as vendor_type'); // Ensure to select the type

                    $users_db->orderBy('vendor_type', $sort_order);
                    break;

                case 'total_rating':
                    // order by the total rating
                    // using left join
                    $users_db->leftJoin('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
                        ->select('users.*', 'vendor_user_details.total_rating as total_rating'); // Ensure to select the total rating

                    $users_db->orderBy('total_rating', $sort_order);
                    break;

                case 'gender':
                    // order by the gender
                    // using left join
                    $users_db->leftJoin('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
                        ->select('users.*', 'vendor_user_details.gender as sort_gender');

                    $users_db->orderBy('sort_gender', $sort_order);

                    break;

                    case 'age':
                        // order by the age
                        // using left join
                        $users_db->leftJoin('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
                            ->select('users.*', 'vendor_user_details.date_of_birth as sort_date_of_birth');

                        $users_db->orderBy('sort_date_of_birth', $sort_order === "asc" ? "desc" : "asc"); // alternate the order by the date of birth as age is reverse of date of birth

                        break;

                case 'working_dates':
                    // order by the availability_from
                    // using left join
                    $users_db->leftJoin('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
                        ->select('users.*', 'vendor_user_details.availability_from as sort_availability_from');

                    $users_db->orderBy('sort_availability_from', $sort_order);

                    break;

                default:
                    // order the queries which can be directly order
                    $users_db->orderBy($sort_name, $sort_order);
                    break;
            }


            // _________________________________________________________________________________________________

        } else {

            // If no sort name then order by the id
            $users_db->orderByRaw('COALESCE(updated_at, created_at) DESC');

        }


        // ------------------------------------------



        if ($export) {
            return $this->excelReporting($users_db);
        }

       //dd($users_db->toSql(), $users_db->getBindings());

        $vendors = $users_db->paginate(10);

        //dd($vendors[0]);


        $types = $this->types();

        $categories = Categories::where('active', 1)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return view('admin.vendors.list', compact('page_heading', 'vendors', 'types', 'categories', 'disableSortingColumnsIndex'));
    }


    public function create()
    {
        if (!get_user_permission('vendors', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Add Artist";

        $id = "";
        $user_image = "";
        $first_name = "";
        $last_name = "";
        $gender = "male";
        $username = "";
        $email = "";
        $dial_code = "";
        $phone = "";
        $location_name = "";
        $lattitude = "";
        $longitude = "";
        $countries = CountryModel::getCountries();
        $date_of_birth = "";
        $about = "";
        $instagram = "";
        $twitter = "";
        $facebook = "";
        $tiktok = "";
        $thread = "";
        $about = "";
        $hourly_rate = "";
        $deposit_amount = "";
        $c_policy = "";
        $r_policy = "";
        $reference_number = "";
        $availability_to = "";
        // $hourly_rate = "";
        // $advance_percent = "";
        $availability_from = "";
        $active = "1";

        $return_policies = [];

        // Get the categories with active 1, order by sort_order and then id
        $categories = Categories::where('active', 1)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();
        $category_id = '';

        $type = 'resident';

        $types = $this->types();
        $selectedcat = [];


        $main_type = 'admin';

        return view('admin.vendors.create', compact('page_heading', 'main_type', 'id', 'selectedcat', 'user_image', 'first_name', 'last_name', 'gender', 'username', 'email', 'dial_code', 'phone', 'location_name', 'lattitude', 'longitude', 'countries', 'date_of_birth', 'about', 'instagram', 'twitter', 'facebook', 'tiktok', 'c_policy', 'r_policy', 'reference_number',  'availability_from', 'active', 'return_policies', 'categories', 'type', 'types', 'category_id', 'thread', 'about', 'hourly_rate', 'deposit_amount', 'availability_to'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {

        if (!get_user_permission('vendors', 'u')) {
            return redirect()->route('admin.restricted_page');
        }


        // Request get query variable isartisit
        $isartist = $request->isartist ?? null;


        $main_type = $isartist ? 'vendor' : 'admin';


        // If $isartist then check if the user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($isartist && $id != Auth::id()) {

            return redirect()->route('vendor.restricted_page');
        } else if (!$isartist) {

            // If it's not admin user
            if (Auth::user()->user_type_id !== 1) {
                return redirect()->route('admin.restricted_page');
            }
        }


        // Follow the create function codes pattern
        $page_heading = "Edit Artist";
        // @todo do security thing, if trying to access other type user then trigger error
        $vendor = User::findOrFail($id);
        $vendor_user_details = VendorUserDetail::where('user_id', $id)->first();
        $id = $vendor->id;
        $user_image = $vendor->user_image;
        $first_name = $vendor->first_name;
        $last_name = $vendor->last_name;
        $gender = $vendor_user_details->gender;
        $username = $vendor_user_details->username;
        $email = $vendor->email;
        $dial_code = $vendor->dial_code;
        $phone = $vendor->phone;
        $location_name = $vendor_user_details->location_name;
        $lattitude = $vendor_user_details->lattitude;
        $longitude = $vendor_user_details->longitude;
        $countries = CountryModel::getCountries();
        $date_of_birth = $vendor_user_details->date_of_birth;
        $about = $vendor_user_details->about;
        $instagram = $vendor_user_details->instagram;
        $twitter = $vendor_user_details->twitter;
        $facebook = $vendor_user_details->facebook;
        $tiktok = $vendor_user_details->tiktok;
        $thread = $vendor_user_details->thread;
        $about = $vendor_user_details->about;
        $hourly_rate = $vendor_user_details->hourly_rate;
        $deposit_amount = $vendor_user_details->deposit_amount;
        $c_policy = $vendor_user_details->c_policy;
        $r_policy = $vendor_user_details->r_policy;
        $reference_number = $vendor_user_details->reference_number;
        // $hourly_rate = $vendor_user_details->hourly_rate;
        // $advance_percent = $vendor_user_details->advance_percent;
        $availability_from = $vendor_user_details->availability_from;
        $availability_to = $vendor_user_details->availability_to;
        $active = $vendor->active;
        $category_id = $vendor_user_details->category_id;
        $selectedcat = $vendor_user_details->categories;
        // $selectedcat = explode(",", $vendor_user_details->categories);

        //dd($vendor_user_details);

        // try to decode the r_policy json if not then set empty array
        $return_policies = null;
        if ($r_policy) {
            try {
                $return_policies = json_decode($r_policy);
            } catch (\Exception $e) {
                // Handle the exception here
            }
        }

        // if $return_policies is null then set empty array
        if (!$return_policies) {
            $return_policies = [];
        }

        // Get the categories with active 1, order by sort_order and then id
        $categories = Categories::where('active', 1)->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        $type = $vendor_user_details->type;

        $types = $this->types();

        return view('admin.vendors.create', compact('page_heading', 'main_type', 'id', 'selectedcat', 'user_image', 'first_name', 'last_name', 'gender', 'username', 'email', 'dial_code', 'phone', 'location_name', 'lattitude', 'longitude', 'countries', 'date_of_birth', 'about', 'instagram', 'twitter', 'facebook', 'tiktok', 'c_policy', 'reference_number', 'availability_from', 'active', 'return_policies', 'categories', 'type', 'types', 'category_id', 'thread', 'about', 'hourly_rate', 'deposit_amount', 'availability_to'));
    }


    // Function to change the active status of the vendor
    public function change_status(Request $request)
    {
        $status = "0";
        $message = "";
        if (User::where('id', $request->id)->update(['active' => $request->status])) {
            $status = "1";
            $msg = "Successfully activated";
            if (!$request->status) {
                $msg = "Successfully deactivated";
            }
            $message = $msg;
        } else {
            $message = "Something went wrong";
        }

        echo json_encode(['status' => $status, 'message' => $message]);
    }


    // create the store function
    public function store(Request $request)
    {



        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };


        $rules = [
            'first_name' => !$request->id ? 'required' : '',
            'last_name' => !$request->id ? 'required' : '',
            //'date_of_birth' => !$request->id ? 'required|date_format:Y-m-d' : '',
            //'gender' => !$request->id ? 'required' : '',
            'phone' => !$request->id ? 'required|numeric' : '',
            'dial_code' => !$request->id ? 'required|numeric' : '',
            'user_image' => !$request->id ? 'required|image|mimes:jpeg,png,jpg|max:5120' : '',
            'username' => [
                !$request->id ? 'required' : '',
                // Enforce unique rule, ignoring the current record if $request->id is provided
                Rule::unique('vendor_user_details', 'username')->ignore($request->id, 'user_id')
            ],
            'about' => !$request->id ? 'required' : '',
            'hourly_rate' => !$request->id ? 'required|numeric' : '',
            'deposit_amount' => !$request->id ? 'required|numeric' : '',
            'availability_from' => !$request->id ? 'required|date_format:d-m-Y' : '',
            // required date and should be greater than the availability_from date
            'availability_to' => !$request->id ? 'required|date_format:d-m-Y|after:availability_from' : '',

        ];
        if($request->availability_to){
            $request->availability_to=Carbon::createFromFormat('d-m-Y',  $request->availability_to)->format('Y-m-d');
        }
        if($request->availability_from){
            $request->availability_from=Carbon::createFromFormat('d-m-Y',  $request->availability_from)->format('Y-m-d');
        }


        // Validate the request with the following fields as required, first_name, last_name, email, phone, location_name, lattitude, longitude
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // return with  $returnResponse$validator->errors()->first();
            return $returnResponse(0, $validator->errors()->first(), $validator->errors());
        }


        // lowercase email if provided
        if ($request->email) {
            $request->email = strtolower($request->email);
        }


        // Request get query variable isartisit
        $isartist = $request->isartist ?? null;

        // If $isartist then check if the user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($isartist && $request->id != Auth::id()) {
            return $returnResponse(0, "Unauthorized access");
        } else if (!$isartist) {

            // If it's not admin user
            if (Auth::user()->user_type_id !== 1) {
                return $returnResponse(0, "Unauthorized access");
            }
        }


        // if id is not provided then validate the password field
        if (!$request->id) {
            $validator = Validator::make($request->all(), [
                'password' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'confirm_password' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                // return with  $returnResponse
                return $returnResponse(0, $validator->errors()->first(), $validator->errors());
            }
        }



        // Extract the long and lat from the location
        $lat = "";
        $long = "";
        if ($request->location) {
            $location = explode(",", $request->location);
            $lat = $location[0];
            $long = $location[1];
        }


        // create the user
        if ($request->id) {
            $user = User::find($request->id);
            if (!$user) {
                return $returnResponse(0, "User not found");
            }
        } else {
            $user = new User();
            $user->phone_verified = 1;
            $user->verified = 1;
            $user->active = 1;
            $user->user_type_id = 3;
            $user->email = $request->email;
        }


        //

        // check if the email already exists in the database
        if ($request->email) {
            $check_exist = User::where('email', $request->email)->where('id','!=',$request->id)->get()->toArray();
            if (!empty($check_exist)) {
                return $returnResponse(0, "Email already exists");
            }

            $user->email = $request->email;
        }


        // check if the phone and dial code is already exists in the database
        // If request and user phone is not same then check if the phone exists
        if ($request->phone != $user->phone && $request->dial_code != $user->dial_code) {
            $check_exist = User::where('phone', $request->phone)->where('dial_code', $request->dial_code)->get()->toArray();
            if (!empty($check_exist)) {
                return $returnResponse(0, "Phone already exists");
            }
        }


        // If id is not provided then validate if the username and email provided
        if (!$request->id) {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                // return with  $returnResponse
                return $returnResponse(0, $validator->errors()->first(), $validator->errors());
            }
        }



        // If id is not provided then check if the username already exists
        if (!$request->id) {
            $check_exist = VendorUserDetail::where('username', $request->username)->get()->toArray();
            if (!empty($check_exist)) {
                return $returnResponse(0, "Username already exists");
            }
        }



        if (!empty($request->first_name)) {
            $user->first_name = $request->first_name;
        }

        if (!empty($request->last_name)) {
            $user->last_name = $request->last_name;
        }

        if (!empty($request->first_name) || !empty($request->last_name)) {

            $iFirstName = empty($request->first_name) ? $user->first_name : $request->first_name;
            $iLastName = empty($request->last_name) ? $user->last_name : $request->last_name;


            $user->name = $iFirstName . " " . $iLastName;
        }

        if (!empty($request->phone)) {
            $user->phone = $request->phone;
        }

        if (!empty($request->dial_code)) {
            $user->dial_code = $request->dial_code;
        }



        // if password is provided then set the password
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }


        // Upload image if provided
        if ($request->file("user_image")) {

            $response = image_upload($request, 'vendor_user', 'user_image');
            if ($response['status']) {
                $user->user_image = $response['link'];
            }
        }



        // Start a database transaction
        DB::beginTransaction();

        try {

            $user->save();

            // Once the user is created successfully, create or update the vendor_user_details
            $vendor_user_details = VendorUserDetail::where('user_id', $user->id)->first();
            if (!$vendor_user_details) {
                $vendor_user_details = new VendorUserDetail();
            }




            // If return_policies is provided then add the return_policies
            // @deprecated: As reschedule policies are shifted to the global cms page
            // if ($request->return_policies) {

            //     // Validate if the return_policies is in the array format
            //     if (!is_array($request->return_policies)) {
            //         return $returnResponse(0, "Return policies should be in array format");
            //     }


            //     // Loop through the return_policies and add to the array with the 0 index and keep incrementing
            //     $iindex = 0;
            //     $formated_return_policies = [];
            //     foreach ($request->return_policies as $policy) {

            //         $policy = json_decode($policy, true);

            //         $formated_return_policies[$iindex] = [
            //             'dayStart' => $policy["dayStart"],
            //             'dayEnd' => $policy["dayEnd"],
            //             'amount' => $policy["amount"],
            //         ];

            //         $iindex++;
            //     }


            //     // Save as array json
            //     $vendor_user_details->r_policy = json_encode($formated_return_policies);
            // }




            $vendor_user_details->user_id = $user->id;

            if (!empty($request->location_name)) {
                $vendor_user_details->location_name = $request->location_name;
            }

            if (!empty($request->location)) {
                $location = explode(",", $request->location);
                $vendor_user_details->lattitude = $location[0];
                $vendor_user_details->longitude = $location[1];
            }

            if (!empty($request->about)) {
                $vendor_user_details->about = $request->about;
            }

            // if (!empty($request->instagram)) {
                $vendor_user_details->instagram = $request->instagram;
            // }

            // if (!empty($request->twitter)) {
                $vendor_user_details->twitter = $request->twitter;
            // }

            // if (!empty($request->facebook)) {
                $vendor_user_details->facebook = $request->facebook;
            // }

            // if (!empty($request->tiktok)) {
                $vendor_user_details->tiktok = $request->tiktok;
            // }

            // if (!empty($request->thread)) {
                $vendor_user_details->thread = $request->thread;
            // }

            if (!empty($request->c_policy)) {
                $vendor_user_details->c_policy = $request->c_policy;
            }

            $vendor_user_details->reference_number = mt_rand(100000, 999999);

            if (!empty($request->hourly_rate)) {
                $vendor_user_details->hourly_rate = $request->hourly_rate;
            }

            if (!empty($request->deposit_amount)) {
                $vendor_user_details->deposit_amount = $request->deposit_amount;
            }

            if (!empty($request->availability_from)) {
                $vendor_user_details->availability_from = date('Y-m-d', strtotime($request->availability_from));
            }

            if (!empty($request->availability_to)) {
                $vendor_user_details->availability_to = date('Y-m-d', strtotime($request->availability_to));
            }

            if (!empty($request->category_id)) {
                $vendor_user_details->categories = implode(",", $request->category_id);
            }

            if (!empty($request->category_id)) {
                $vendor_user_details->category_id = $request->category_id[0] ?? 0;
            }

            if (!empty($request->type)) {
                $vendor_user_details->type = $request->type;
            }



            // Add the date_of_birth, gender
            if ($request->date_of_birth) {
                $vendor_user_details->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
            }

            // if gender provided in request then add
            if ($request->gender) {
                $vendor_user_details->gender = $request->gender;
            }


            // if username is provided then add
            if ($request->username) {
                $vendor_user_details->username = $request->username;
            }



            // Save the vendor_user_details
            $vendor_user_details->save();


            // Message based on the id provided it's update else vendor added successfully
            $message = "Artist added successfully";
            if ($request->id) {
                $message = "Artist updated successfully";
            }


            // Commit the transaction
            DB::commit();

            return $returnResponse(1, $message);
        } catch (\Exception $e) {
            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception or log the error
            $error = $e->getMessage();

            return $returnResponse(0, "Something went wrong", [], $error);
        }
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $status = "0";
        $message = "";
        $o_data = [];

        $validator = Validator::make($request->all(), [
            'itemId' => 'required',
        ]);


        if ($validator->fails()) {

            $status = "0";
            $message = "Validation error occured";
            $errors = $validator->messages();
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors]);
        }

        // If the current user is not user_type 1 then return error
        if (auth()->user()->user_type_id != 1) {
           return response()->error("Only admin user can perform this action");
        }

        $id = $request->itemId;


        // check if there are bookings for this user
        $booking = VendorBooking::where('user_id', $id)->first();

        // check if this category is used in any then returnt error
        if ($booking) {
            return response()->error("You cannot delete this artist as there are booking for this artist!");
        }



        $item = User::find($id);
        if ($item) {

            // Soft delete
            $item->delete();

            $status = "1";
            $message = "Artist removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        return json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }


    private function types()
    {
        return [
            'resident' => 'Resident',
            'guest' => 'Guest',
        ];
    }


    public function artistShareLink(Request $request, $vendor_id)
    {

        // Get the ios and play store app link from .evn
        $ios_app_link = env('IOS_APP_LINK') ?? 'https://apps.apple.com/app/disraption/id6504341332';
        $play_store_app_link = env('PLAY_STORE_APP_LINK') ?? 'https://play.google.com/store/apps/details?id=com.soouq';

        $agent = new Agent();

        if ($agent->isAndroid()) {
            // redirect
            return redirect($play_store_app_link);
        } elseif ($agent->isiOS()) {

            return redirect($ios_app_link);
        }
        //echo $ios_app_link;
        return redirect($ios_app_link);
    }

    private function excelReporting($users_db)
    {

        $list = $users_db->get();
        $rows = array();
        $i = 1;
        foreach ($list as $key => $val) {

            $rows[$key]['i'] = $i;
            $rows[$key]['name'] = $val->name;
            $rows[$key]['email'] = $val->email;
            $rows[$key]['phone'] = '+' . ($val->dial_code != '') ? $val->dial_code . ' ' . $val->phone : '-';
            // Date of birth
            $rows[$key]['dob'] = $val->vendor_details->date_of_birth ? self::calculateAge($val->vendor_details->date_of_birth) : "N/a";
            // gender in capitalize
            $rows[$key]['gender'] = ucfirst($val->vendor_details->gender);
            // Category name
            $rows[$key]['category'] = ucfirst($val->vendor_details->category->name);
            $rows[$key]['type'] = ucfirst($val->vendor_details->type);
            $rows[$key]['sales_rating'] = self::salesRating($val) . "%";
            $rows[$key]['location'] = $val->vendor_details->location_name;

            $fromDate = $val->vendor_details->availability_from ?? "N/a";
            $toDate = $val->vendor_details->availability_to ?? "";

            // availablity from and to with line break and check for each date if avalualbe then output else empty for that date
            $rows[$key]['working_dates'] = $fromDate . ($toDate ? ' - ' . $toDate : '');
            $rows[$key]['verified'] = (int)$val->verified ? 'Yes' : 'No';
            $rows[$key]['active'] = (int)$val->active ? 'Yes' : 'No';
            $rows[$key]['created_date'] = web_date_in_timezone($val->created_at, 'd-m-y h:i A');

            $i++;
        }

        $headings = [
            "#",
            "Name",
            "Email",
            "Mobile",
            "Age",
            "Gender",
            "Style",
            "Type",
            "Sales Rating",
            "Location",
            "Working Dates",
            "Verified",
            "Active",
            "Created Date",
        ];


        // Generate filename
        $file_name = 'artist_' . date('d_m_Y_h_i_s') . '.xlsx';


        return ExcelExporter::exportToExcel($headings, $rows, $file_name);
    }


    public function apiGetHome(Request $request)
    {

        $banners = AppBanner::select(["id", "name", "banner_image"])->where(['active' => 1])->orderBy('sort_order', 'asc')->get()->toArray();
        $settings = PagesController::getAllSettings();
        $categories = Categories::select(["id", "name", "image"])->where(['active' => 1])->orderBy('sort_order', 'asc')->limit(10)->get()->toArray();
        $guestArtists = User::with([
            'vendor_details.category',
            'vendorRatings.customer' => function ($query) {
                $query->select('id', 'name');
            }
        ])
        ->where('user_type_id', 3)
        ->whereHas('vendor_details', function ($query) {
            $query->where('type', 'guest');
        })
        ->limit(6)
        ->get()->toArray();


        $residentArtists = User::with([
            'vendor_details.category',
            'vendorRatings.customer' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->where('user_type_id', 3)
            ->whereHas('vendor_details', function ($query) {
                $query->where('type', 'resident');
            })
            ->limit(6)
            ->get()->toArray();


        $topRatedArtists = User::with([
            'vendor_details.category',
            'vendorRatings.customer' => function ($query) {
                $query->select('id', 'name');
            }
        ])->whereHas('vendor_details', function ($query) {
            $query->whereBetween('total_rating', [4.5, 5]);
        })
            ->join('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
            ->where('user_type_id', 3)
            ->orderBy('vendor_user_details.total_rating', 'desc')
            ->select('users.*')
            ->limit(6)
            ->get()->toArray();

        $fastSelling = $residentArtists;

        $cookingSoon = $residentArtists;


        $returnData = [
            'banners' => $banners,
            'settings' => $settings,
            'categories' => $categories,
            'guestArtists' => $guestArtists,
            'residentArtists' => $residentArtists,
            'topRatedArtists' => $topRatedArtists,
            'fastSelling' => $fastSelling,
            'comingSoon' => $cookingSoon,

        ];

        $returnData = convert_all_elements_to_string($returnData);

        return response()->success('Home data fetched successfully', $returnData);
    }

    public function apiGetAllArtists(Request $request)
    {

        $limit = $request->limit ?? 10;

        // Types keys in comma separated
        $types_keys = [
            'resident',
            'guest',
            'topRated',
            'fastSelling',
            'comingSoon'
        ];

        // array to comma separated
        $types_keys = implode(',', $types_keys);

        // remove white spaces
        $types_keys = str_replace(' ', '', $types_keys);


        // if types are provided then it should be resident or guest, check by validation rule
        $validator = Validator::make($request->all(), [
            'type' => 'in:' . $types_keys,
        ]);

        if ($validator->fails()) {
            return response()->error('Invalid type');
        }



        $type = $request->type ?? null;


        $artists_query = User::with(['vendor_details.category', 'vendorRatings.customer' => function ($query) {
            $query->select('id', 'name');
        }])
            ->where('user_type_id', 3);

            if($type == 'topRated'){
                $artists_query = $artists_query->whereHas('vendor_details', function ($query) {
                    $query->whereBetween('total_rating', [4.5, 5]);
                });
            }

        // Ready db types
        $dbTypes = $this->types();


        // If type prodivded and it's in the dbtypes
        if ($type && in_array($type, array_keys($dbTypes))) {

            $artists_query->whereHas('vendor_details', function ($query) use ($type) {
                $query->where('type', $type);
            });
        }

        if($type=='guest' || $type=='comingSoon'){
            $artists_query->whereHas('vendor_details', function ($query) {
                $query->orderBy('availability_from', 'asc');  // Ordering by availability_to in vendor_details relationship
            });

        }




        // if $request->top_rated is provided then order by rating
        if ($request->top_rated || $type == 'topRated') {
            $artists_query->whereHas('vendor_details', function ($query) {
                $query->where('type', 'guest')
                      ->whereBetween('total_rating', [4.5, 5]);  // Filtering by total_rating between 4.5 and 5
            });
            $artists_query->join('vendor_user_details', 'users.id', '=', 'vendor_user_details.user_id')
                ->orderBy('vendor_user_details.total_rating', 'desc')
                ->select('users.*');
        }


        // @todo do for the fast selling and coming soon


        $artists = $artists_query->paginate($limit);

        $artists = cleanPaginationResultArray($artists->toArray());
        $artists = convert_all_elements_to_string($artists);

        return response()->json(['status' => 1, 'message' => 'Artists fetched successfully', 'data' => $artists]);
    }


    public function apiSearchArtists(Request $request)
    {

        $limit = $request->limit ?? 10;

        $name = $request->name ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $category_id = $request->category_id ?? null;
        $type = $request->type ?? null;
        $gender = $request->gender ?? null;

        $users_db = User::with(['vendor_details.category', 'vendorRatings.customer' => function ($query) {
            $query->select('id', 'name');
        }])
            ->where('user_type_id', 3);

        // Apply conditions on the vendor_details relationship
        if ($category_id) {
            $users_db->whereHas('vendor_details', function ($query) use ($category_id) {
                // Use PostgreSQL's string_to_array and ANY function to check if category_id exists
                $query->whereRaw('? = ANY(string_to_array(categories, \',\'))', [$category_id]);
            });
        }

        if ($type) {
            $users_db->whereHas('vendor_details', function ($query) use ($type) {
                $query->where('type', $type);
            });
        }

        if ($gender) {
            $users_db->whereHas('vendor_details', function ($query) use ($gender) {
                $query->where('gender', $gender);
            });
        }


        // Apply other conditions
        if ($name) {
            $users_db->whereRaw('LOWER(users.name) LIKE ?', ['%' . strtolower($name) . '%']);
        }


        if ($from_date != '') {
            $users_db->whereHas('vendor_details', function ($query) use ($from_date) {
                $query->whereDate('availability_from', '<=', $from_date);
            });
        }


        if ($to_date != '') {
            $users_db->whereHas('vendor_details', function ($query) use ($to_date) {
                $query->whereDate('availability_to', '>=', $to_date);
            });
        }

        $artists = $users_db->paginate($limit);

        $artists = cleanPaginationResultArray($artists->toArray());
        $artists = convert_all_elements_to_string($artists);

        return response()->json(['status' => 1, 'message' => 'Artists fetched successfully', 'data' => $artists]);
    }

    public function apiGetTopRatedArtists(Request $request)
    {

        // Add the top_rated key in the request
        $request->top_rated = 1;

        return $this->apiGetAllArtists($request);
    }


    public function apiGetArtist(Request $request)
    {

        // validate the request
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->error('Invalid request', $validator->messages());
        }

        $vendor_id = $request->id;


        $user = AuthenticateWithSanctum::authenticateGetUser($request);

        $customerUserID = $user ? $user->id : null;


        $artist = User::with([
            'vendor_details.category',

            'vendorRatings.customer' => function ($query) {
                $query->select('id', 'name');
            },
            'vendorPortfolio' => function ($query) {
                $query->orderBy('sort_order', 'asc')->take(10);
            }
        ])
            ->leftJoin('favourites', function ($join) use ($customerUserID) {
                $join->on('users.id', '=', 'favourites.vendor_id')
                    ->where('favourites.customer_id', '=', $customerUserID);
            })
            ->select('users.*', DB::raw('CASE WHEN favourites.id IS NOT NULL THEN 1 ELSE 0 END AS is_favourite'))
            ->where('user_type_id', 3)
            ->where('users.id', $vendor_id)
            ->first();
           // dd($artist->vendor_details->categories);
            $categories=$artist->vendor_details->categories;
            $categories_list=[];
            if(!empty($categories)){
                foreach($categories as $category){

                    $categories_exist=Categories::find($category);

                    if($categories_exist){
                        $categories_list[]=$categories_exist->name;
                    }
                }

            }

        if (!$artist) {
            return response()->error('Artist not found');
        }
        $artist->categories_list=$categories_list;

        $artist = convert_all_elements_to_string($artist->toArray());



        return response()->success('Artist fetched successfully', $artist);
    }


    public function apiGetVendorPortfolio(Request $request)
    {

        try {

            $limit = $request->limit ?? 10;

            // validate the request
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->error('Invalid request', $validator->messages());
            }

            $vendor_id = $request->id;

            $portfolio = VendorPortfolio::where('user_id', $vendor_id)
                ->orderBy('sort_order', 'asc')
                ->paginate($limit);

            if (!$portfolio) {
                return response()->error('Portfolio not found');
            }


            $portfolio = cleanPaginationResultArray($portfolio->toArray());
            $portfolio = convert_all_elements_to_string($portfolio);


            return response()->success('Portfolio fetched successfully', $portfolio);
        } catch (\Throwable $th) {
            return response()->error('Something went wrong', $th->getMessage());
        }
    }

    public function apiGetArtistReviews(Request $request)
    {

        try {

            // validate the request
            $validator = Validator::make($request->all(), [
                'id' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->error('Invalid request', $validator->messages());
            }

            $vendor_id = $request->id;

            $limit = $request->limit ?? 10;

            $artist_reviews = VendorRating::with(['customer' => function ($query) {
                $query->select('id', 'name');
            }])
                ->where('vendor_id', $vendor_id)
                ->orderBy('id', 'desc')
                ->paginate($limit);

            if (!$artist_reviews) {
                return response()->error('Artist not found');
            }


            $artist_reviews = cleanPaginationResultArray($artist_reviews->toArray());
            $artist_reviews = convert_all_elements_to_string($artist_reviews);

            return response()->success('Artist ratings fetched successfully', $artist_reviews);
        } catch (\Throwable $th) {
            return response()->error('Something went wrong', $th->getMessage());
        }
    }


    public static function calculateAge($birthDateString)
    {
        // Parse the birth date string
        $birthDate = new DateTime($birthDateString);
        $today = new DateTime('today');

        // Calculate the difference in years
        $age = $today->diff($birthDate)->y;

        return $age;
    }

    public static function salesRating($vendor)
    {

        // --------- Calculate the days ------

        $start_date = new DateTime($vendor->vendor_details->availability_from);
        $end_date = new DateTime($vendor->vendor_details->availability_to);

        $excluded_days = [6, 0]; // 6 is Saturday, 0 is Sunday

        $interval = new DateInterval('P1D'); // 1 day interval
        $period = new DatePeriod($start_date, $interval, $end_date->modify('+1 day')); // Add 1 day to include end date

        $valid_days = 0;

        foreach ($period as $date) {
            $day_of_week = (int)$date->format('N'); // 1 (Monday) to 7 (Sunday)

            if (!in_array($day_of_week, $excluded_days)) {
                $valid_days++;
            }
        }

        // ---------------------

        // calculate from dates availabiity
        $totalHours = $vendor->vendor_bookings_sum_total_hours ?? 0;
        $AllHours = $valid_days * 9; // Each artist have 9 hours of work daily.

        return round(($totalHours / $AllHours) * 100, 2);
    }
}
