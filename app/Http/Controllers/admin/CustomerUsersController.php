<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerUserDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\CountryModel;
use App\Exports\ExcelExporter;
use App\Http\Middleware\AuthenticateWithSanctum;
use App\Models\TempTransaction;
use App\Models\TempUser;
use App\Models\Transaction;
use App\Models\VendorRating;
use App\Payments\PaymentStripe;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\admin\VendorUsersController;
use App\Models\Vendor\VendorBooking;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Carbon\Carbon;


class CustomerUsersController extends Controller
{

    public function deleteCustomers()
    {
        // Find booking order by the order id and vendor id
       // $customer = User::where('user_type_id', 2)->delete();
       // DB::statement('DELETE FROM "contact_us_entries"');

        // DB::statement('DELETE FROM "public"."users" WHERE "email" != :email AND "user_type_id" = :user_type_id', [
        //     'email' => 'x95y47zzfm@privaterelay.appleid.com',
        //     'user_type_id' => 2,
        // ]);
        DB::statement('UPDATE "public"."users" SET "deleted_at" = NULL WHERE "email" = :email AND "user_type_id" = :user_type_id', [
            'email' => 'x95y47zzfm@privaterelay.appleid.com',
            'user_type_id' => 2,
        ]);


        echo 'bookings deleted';
    }
    public function index(Request $request)
    {


        $reporting = $request->reporting ?? null;


        // If it's not a reporting page then check the permission
        if (!$reporting && !get_user_permission('customers', 'r')) {
            return redirect()->route('admin.restricted_page');
        } else if ($reporting && !get_user_permission('reporting_customers', 'r')) {
            return redirect()->route('admin.restricted_page');
        }


        // Get the query parameters
        $name = $request->name ?? null;
        $status = $request->status ?? null;
        $verified = $request->verified ?? null;
        $from_date = $request->from_date ?? null;
        $to_date = $request->to_date ?? null;
        $export = $request->export ?? null;
        $username = $request->username ?? null;

        // Append "Reporting" if the $reporting is not null
        $page_heading = "Customers" . ($reporting ? " Report" : "");

        $users_db = User::with([
            'customerUserDetail'
        ])->where('user_type_id', 2)->where('phone_verified',1);



        $users_db->withAvg('vendorToCustomerRatings', 'rating');


        $users_db->withCount("customerUserBookings");


        $users_db->withSum("customerUserBookings", "total_paid");


        $users_db->withCount("customerRatingsToVendor");



        // If the name is not null then search the name lower case the db name and query name
        if ($name) {
            $users_db->whereRaw('LOWER(users.name) like ?', ['%' . strtolower($name) . '%']);
        }

        if ($username) {
            $users_db->whereRaw('LOWER(users.user_name) like ?', ['%' . strtolower($username) . '%']);
        }

        // If the status is not null then search the status
        if ($status !== null) {
            $users_db->where('users.active', $status);
        }


        // If the verified is not null then search the verified
        if ($verified !== null) {
            $users_db->where('users.verified', $verified);
        }

        // If the from_date is not null then search the from_date
        if ($from_date != '') {
           //$from_date=$from_date.' 00:00:00';
           $from_date = Carbon::createFromFormat('d-m-Y', $from_date)->startOfDay()->format('Y-m-d H:i:s');
            $list = $users_db->where('users.created_at', '>=', $from_date);
        }

        // If the to_date is not null then search the to_date
        if ($to_date != '') {
            $to_date = Carbon::createFromFormat('d-m-Y', $to_date)->EndOfDay()->format('Y-m-d H:i:s');
            $list = $users_db->where('users.created_at', '<=', $to_date);
        }



        // -------------- Table ordering ------------
        $disableSortingColumnsIndex = [0, 1, 2, 9];
        $tableColumnsIndexMaping = array(
            // 0-> row number,
            // 1-> actions
            2 => 'users.first_name',
            3 => 'rating',
            4 => 'no_of_bookings',
            5 => 'total_sales',
            6 => 'age',
            7 => 'gender',
            8 => 'artist_rating',
            9 => 'users.active',
            10 => 'users.last_login',
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

                case 'rating':

                    $users_db->orderBy('vendor_to_customer_ratings_avg_rating', $sort_order);
                    break;

                case 'artist_rating':
                    // // order by the total rating
                    // // using left join
                    // $users_db->leftJoin('customer_user_details', 'users.id', '=', 'customer_user_details.user_id')
                    //     ->select('users.*', 'customer_user_details.total_rating as total_rating'); // Ensure to select the total rating

                    $users_db->orderBy('customer_ratings_to_vendor_count', $sort_order);
                    break;

                case 'no_of_bookings':
                    // order by the total bookings
                    // using with count
                    $users_db->orderBy('customer_user_bookings_count', $sort_order);
                    break;

                case 'total_sales':
                    // order by the total sales
                    // using with sum

                    $users_db->leftJoin('vendor_bookings', 'users.id', '=', 'vendor_bookings.user_id')
                        ->select('users.*', DB::raw('COALESCE(SUM(vendor_bookings.total_paid), 0.00) as customer_user_bookings_sum_total_paid'));
                    $users_db->groupBy('users.id');

                    $users_db->orderBy('customer_user_bookings_sum_total_paid', $sort_order);

                    //$users_db->orderByRaw('COALESCE(customer_user_bookings_sum_total_paid, 0.00) ' . $sort_order);

                    break;

                case 'gender':
                    // order by the gender
                    // using left join
                    $users_db->leftJoin('customer_user_details', 'users.id', '=', 'customer_user_details.user_id')
                        ->select('users.*', 'customer_user_details.gender as sort_gender');

                    $users_db->orderBy('sort_gender', $sort_order);

                    break;

                case 'age':
                    // order by the age
                    // using left join
                    $users_db->leftJoin('customer_user_details', 'users.id', '=', 'customer_user_details.user_id')
                        ->select('users.*', 'customer_user_details.date_of_birth as sort_date_of_birth');

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

            // Default sorting
            $users_db->orderByRaw('COALESCE(updated_at, created_at) DESC');
        }


        // ------------------------------------------



        if ($export) {
            return $this->excelReporting($users_db);
        }



        //dd($users_db->toSql(), $users_db->getBindings());


        $users = $users_db->paginate(10);
        // foreach($users as $key=>$value)
        // {
        //     $users[$key]->ratings = VendorRating::where('user_id',$value->id)->get()->count();
        // }

        //dd($users->items());

        return view('admin.customers.list', compact('page_heading', 'users', 'disableSortingColumnsIndex'));
    }


    public function create()
    {
        if (!get_user_permission('customers', 'c')) {
            return redirect()->route('admin.restricted_page');
        }

        $page_heading = "Add Customer";

        $id = "";
        $is_social = "";
        $name = "";
        $first_name = "";
        $last_name = "";
        $user_image = "";
        $gender = "male";
        $email = "";
        $dial_code = "";
        $phone = "";
        $location_name = "";
        $lattitude = "";
        $longitude = "";
        $date_of_birth = "";
        $active = "1";
        $countries = CountryModel::getCountries();
        $remarks = "";
        $username = "";

        return view('admin.customers.create', compact('page_heading', 'id', 'is_social','username', 'name', 'first_name', 'last_name', 'user_image', 'gender', 'email', 'dial_code', 'phone', 'location_name', 'lattitude', 'longitude', 'date_of_birth', 'active', 'countries', 'remarks'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        if (!get_user_permission('customers', 'u')) {
            return redirect()->route('admin.restricted_page');
        }

        // Follow the create function codes pattern
        $page_heading = "Edit Customer";
        // @todo do security thing, if trying to access other type user then trigger error

        $user = User::findOrFail($id);
        $customer_user_details = CustomerUserDetail::where('user_id', $id)->first();

        $is_social = $customer_user_details->is_social;
        $name = $user->name;
        $username = $user->user_name;
        $first_name = $user->first_name;
        $last_name = $user->last_name;
        $user_image = $user->user_image;
        $gender = $customer_user_details->gender;
        $email = $user->email;
        $dial_code = $user->dial_code;
        $phone = $user->phone;
        $location_name = $customer_user_details->location_name;
        $lattitude = $customer_user_details->lattitude;
        $longitude = $customer_user_details->longitude;
        $date_of_birth = $customer_user_details->date_of_birth;
        $active = $user->active;
        $remarks = $customer_user_details->remarks;
        $countries = CountryModel::getCountries();


        return view('admin.customers.create', compact('page_heading', 'id', 'username','is_social', 'name', 'first_name', 'last_name', 'user_image', 'gender', 'email', 'dial_code', 'phone', 'location_name', 'lattitude', 'longitude', 'date_of_birth', 'active', 'countries', 'remarks'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_Status(Request $request)
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

        // If not admin
        if (!isAdmin()) {
            return response()->unauthorized();
        }


        return $this->registerUser($request);
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
        $booking = VendorBooking::where('customer_id', $id)->first();

        // check if this category is used in any then returnt error
        if ($booking) {
            return response()->error("You cannot delete this customer as there are booking for this customer!");
        }



        $item = User::find($id);
        if ($item) {

            // Soft delete
            $item->delete();

            $status = "1";
            $message = "Customer removed successfully";
        } else {
            $message = "Sorry!.. You cant do this?";
        }

        return json_encode(['status' => $status, 'message' => $message, 'o_data' => $o_data]);
    }


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
            return $returnResponse("0", "Customer not found");
        }

        // Return the success response
        return $returnResponse("1", "Customer found", [], $user);
    }


    private function generateEmailOtpUser($DbUser)
    {

        // @todo send the email otp

        $DbUser->user_phone_otp = 1234;
    }

    // Used to generate the otp for the temp user for account creation or for the actual user for forget password
    private function generateOtpUser($DbUser)
    {
        // @todo send the phone otp

        $DbUser->user_phone_otp = 1234;
    }


    private function getUserData($user_id, $token = null)
    {

        // GEt the user with the customer details
        $user = User::with('customerUserDetail')->where('id', $user_id)->first();

        // If token is not provided then create a new token for the user
        if (!$token) {
            $token = $user->createToken('api')->plainTextToken;
        }


        $user = $user->toArray();

        $moreInfo = $user["customer_user_detail"];

        // Remove the customer user details from the user array
        unset($user["customer_user_detail"]);


        // get the user data and vendor user details in array
        $data = $user;
        $data['more_info'] = $moreInfo;
        $data['access_token'] = $token;

        $data = convert_all_elements_to_string($data);


        return $data;
    }


    // Function to register the temp user
    private function registerTempUser(Request $request, $user_type_id = 2)
    {


        // We Already call the DB::beginTransaction() on the parent function

        try {

            $oldUserDb = null;

            // If id is not provided then check if the email already exists
            $oldUserDb = TempUser::where('email', $request->email)->first();
            // if have old user then return delete it
            if ($oldUserDb) {
                $oldUserDb->delete();
            }


            // check if the phone and dial code is already exists in the database
            $oldUserDb = TempUser::where('phone', $request->phone)->where('dial_code', $request->dial_code)->first();
            if ($oldUserDb) {
                $oldUserDb->delete();
            }


            // Create the temp user
            $tempUser = new TempUser();
            $tempUser->name = $request->first_name . " " . $request->last_name;
            $tempUser->email = strtolower($request->email);
            $tempUser->dial_code = $request->dial_code;
            $tempUser->phone = $request->phone;
            $tempUser->user_type_id = $user_type_id;
            $tempUser->access_token = bin2hex(random_bytes(16));
            $tempUser->user_data = json_encode($request->all());


            // Generate and update otp
            $this->generateOtpUser($tempUser);

            $tempUser->save();

            Db::commit();

            $data = [
                "access_token" => $tempUser->access_token,
            ];


            return response()->success("User registered successfully, please verify the OTP to continue.", $data);
        } catch (QueryException $e) {
            DB::rollBack();
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    // Function to register the real user
    private function registerUser(Request $request, $tempUser = false, $tempUserDb = null, $isSocialSignup = false)
    {

        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };


        try {


            // ----------------- Rules ----------------

            $rules   = [];

            // if id is not provided then add the required rules
            if (!$request->id) {

                $rules['first_name'] = [
                    'required',
                    'regex:/^[^0-9]*$/', // No numbers allowed
                ];;
                $rules['last_name'] = [
                    'required',
                    'regex:/^[^0-9]*$/', // No numbers allowed
                ];;
                $rules['phone'] = 'required|numeric';
                $rules['dial_code'] = 'required|numeric';
                //$rules['date_of_birth'] = 'required';
                //$rules['gender'] = 'in:male,female,other';
                $rules['email'] = 'required|email|unique:users,email';
                $rules['password'] = 'required|min:8';
            } else {

                // If email is provided then deny the request, because we don't want to update the email
                // if ($request->email) {
                //     return response()->error("Email cannot be updated");
                // }
                // We have commented this because Ajesh want the customer email editable
            }

            // ----------------------------------------




            // Validate the request with the following fields as required, first_name, last_name, email, phone, location_name, lattitude, longitude
            $validator = Validator::make(
                $request->all(),
                $rules
            );


            if ($validator->fails()) {

                $message = "Validation failed";

                // if it's have the email error then return with the email error
                if ($validator->errors()->has('email')) {
                    $message = "Email already exists";
                }

                // return with  $returnResponse
                return response()->error($message, $validator->messages());
            }


            // Add 0 with the $request->is_social if it's not provided
            if (!$request->is_social) {
                $request->is_social = 0;
            }


            // ---- Format strings ------

            // If email is provided then lowercase
            if ($request->email) {
                $request->email = strtolower($request->email);
            }

            // If first name is provided then lowercase
            if ($request->first_name) {
                $request->first_name = strtolower($request->first_name);
            }

            // If last name is provided then lowercase
            if ($request->last_name) {
                $request->last_name = strtolower($request->last_name);
            }



            // ----------------------

            // Extract the long and lat from the location
            $lat = "";
            $long = "";
            if ($request->location) {
                $location = explode(",", $request->location);
                $lat = $location[0];
                $long = $location[1];
            }


            // Find or crete the user based on the id
            if ($request->id) {
                $user = User::find($request->id);
                if (!$user) {
                    return response()->error("Customer not found");
                }
            } else {
                $user = new User();
                $user->phone_verified = 1;
                $user->verified = 1;
                $user->active = 1;
                $user->user_type_id = 2;
                $user->email = $request->email;
            }




            // If the is_social is 1 then generate uid password and set the password
            if ($request->is_social == 1 && !property_exists($user, 'password')) {
                $randomString = bin2hex(random_bytes(8));
                $user->password = bcrypt($randomString);
            }


            // check if the email already exists in the database
            if ($request->email && ($request->email != $user->email)) {
                $check_exist = User::where('email', $request->email)->get()->toArray();
                if (!empty($check_exist)) {
                    return response()->error("Email already exists");
                }
            }

            if ($request->username) {
                $check_exist = User::where('user_name', strtolower($request->username))->where('id','!=',$request->id)->get()->toArray();
                if (!empty($check_exist)) {
                    return response()->error("Username already exists");
                }
            }


            // check if the phone and dial code is already exists in the database
            // If request and user phone is not same then check if the phone exists
            if ($request->phone != $user->phone || $request->dial_code != $user->dial_code) {
                $check_exist = User::where('phone', $request->phone)->where('dial_code', $request->dial_code)->get()->toArray();
                if (!empty($check_exist)) {
                    return response()->error("Phone already exists");
                }
            }



            if ($request->first_name) {
                $user->first_name = $request->first_name;
            }

            if ($request->last_name) {
                $user->last_name = $request->last_name;
            }

            if ($request->email) {
                $user->email = $request->email;
            }


            // If it's not isSocial signup then we will update the phone because isSocial signup is adding the phone on the request phone change to verify the otp etc
            if (!$isSocialSignup) {

                if ($request->phone) {
                    $user->phone = $request->phone;
                }

                if ($request->dial_code) {
                    $user->dial_code = $request->dial_code;
                }
            }




            // If fcm token is provided then set the fcm token
            if ($request->fcm_token) {
                $user->fcm_token = $request->fcm_token;
            }


            // If device type is provided then set the device type
            if ($request->device_type) {
                $user->device_type = $request->device_type;
            }

            // If device cart id is provided then set the device cart id
            if ($request->device_cart_id) {
                $user->device_cart_id = $request->device_cart_id;
            }

            // Update name
            if (!empty($request->first_name) || !empty($request->last_name)) {

                $iFirstName = empty($request->first_name) ? $user->first_name : $request->first_name;
                $iLastName = empty($request->last_name) ? $user->last_name : $request->last_name;


                $user->name = $iFirstName . " " . $iLastName;
            }

            if ($request->username) {
                $user->user_name = strtolower($request->username);
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



            // If temp user then register the temp user so after otp verify we will create the real user
            if ($tempUser) {
                return $this->registerTempUser($request);
            }



            // if the isSocialsignup then request phone change and send otp
            if ($isSocialSignup) {

                $user->req_chng_phone = $request->phone;
                $user->req_chng_dial_code = $request->dial_code;

                // send otp
                $this->generateOtpUser($user);
            }


            // Save the user
            $user->save();



            // Once the user is created successfully, create or update the vendor_user_details
            $customer_user_details = CustomerUserDetail::where('user_id', $user->id)->first();

            if (!$customer_user_details) {
                $customer_user_details = new CustomerUserDetail();
                $customer_user_details->is_social = 0;
            }

            // Update the customer user details
            $customer_user_details->user_id = $user->id;
            $customer_user_details->wallet_id = User::generateWalletId();
            $customer_user_details->lattitude = $lat;
            $customer_user_details->longitude = $long;
            $customer_user_details->location_name = ""; //$request->location_name;



            // if added remarks
            if ($request->remarks) {
                $customer_user_details->remarks = $request->remarks;
            }

            if ($request->date_of_birth) {
                $customer_user_details->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
            }

            if ($request->gender) {

                $customer_user_details->gender = strtolower($request->gender);
            }

            // if id is not provided then set the is_social from the request
            if (!$request->id) {
                $customer_user_details->is_social = $request->is_social;
            }

            // Save the Customer user details
            $customer_user_details->save();

            // If the user is not temp user then delete the temp user
            if ($tempUserDb) {
                $tempUserDb->delete();
            }

            // Commit the transaction
            DB::commit();

            // Message based on the id provided it's update else vendor added successfully
            $message = "Customer added successfully";
            if ($request->id) {
                $message = "Customer updated successfully";
            }


            $token = "";


            // Create a new token for the user if it's not a social signup
            if (!$isSocialSignup) {
                $token = $user->createToken('api')->plainTextToken;
            }


            // GEt the user with the customer details
            $user = User::with('customerUserDetail')->where('id', $user->id)->first()->toArray();
            $moreInfo = $user["customer_user_detail"];

            // Remove the customer user details from the user array
            unset($user["customer_user_detail"]);


            // get the user data and vendor user details in array
            $data = $user;
            $data['more_info'] = $moreInfo;
            $data['access_token'] = $token;

            $data = convert_all_elements_to_string($data);


            return response()->success($message, $data);
        } catch (\Exception $e) {

            // Rollback the transaction in case of any exception
            DB::rollback();

            // Handle the exception or log the error
            $error = $e->getMessage();

            return response()->error("Something went wrong", $error);
        }


        // Message based on the id provided it's update else vendor added successfully
        $message = "Customer added successfully";
        if ($request->id) {
            $message = "Customer updated successfully";
        }

        return response()->success($message);
    }



    // Api call to register the user
    public function apiRegisterUser(Request $request)
    {
        return $this->registerUser($request, true);
    }

    public function apiSocialLoginSignup(Request $request)
    {

        // validate the request first_name, email
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->error($validator->errors()->first(), $validator->errors());
        }

        // Check if the user already exists
        $user = User::where('email', $request->email)->first();

        // --------- If user found and is_verified then create a new token and return the user data -------
        if ($user && $user->verified != 0) {

            $isSaveUser = false;

            // If the fcm token is provided then update the fcm token
            if ($request->fcm_token) {
                $user->fcm_token = $request->fcm_token;
                $isSaveUser = true;
            }

            // If the device type is provided then update the device type
            if ($request->device_type) {
                $user->device_type = $request->device_type;
                $isSaveUser = true;
            }

            // If the device cart id is provided then update the device cart id
            if ($request->device_cart_id) {
                $user->device_cart_id = $request->device_cart_id;
                $isSaveUser = true;
            }

            if ($isSaveUser) {
                $user->save();
            }


            $token = $user->createToken('api')->plainTextToken;
            $data = $this->getUserData($user->id, $token);

            $data = convert_all_elements_to_string($data);

            return response()->success("User found", $data);
        }

        // -------------------------------------------------------------

        // If user found and not verified then return the user object
        if ($user && $user->verified == 0) {
            return response()->success("User found but not verified", convert_all_elements_to_string($this->getUserData($user->id)));
        }


        // Format the user
        $request->name = strtolower($request->first_name);
        $request->email = strtolower($request->email);



        // We are here it means the user is not found so create a new user
        $user = new User();
        $user->email = $request->email;
        $user->name = $request->first_name;
        $user->first_name = $request->first_name;
        $user->user_type_id = 2;
        $user->verified = 0;
        $user->active = 1;
        $user->phone_verified = 0;
        $user->password = bcrypt(bin2hex(random_bytes(8)));
        // dummy random number
        $user->phone = rand(1000000000, 9999999999);

        // If the fcm token is provided then set the fcm token
        if ($request->fcm_token) {
            $user->fcm_token = $request->fcm_token;
        }

        // If the device type is provided then set the device type
        if ($request->device_type) {
            $user->device_type = $request->device_type;
        }

        // If the device cart id is provided then set the device cart id
        if ($request->device_cart_id) {
            $user->device_cart_id = $request->device_cart_id;
        }


        // Start a database transaction
        DB::beginTransaction();

        // Save the user
        $user->save();


        $customer_user_details = new CustomerUserDetail();
        $customer_user_details->is_social = 1;
        $customer_user_details->user_id = $user->id;
        $customer_user_details->wallet_id = User::generateWalletId();
        $customer_user_details->lattitude = "";
        $customer_user_details->longitude = "";
        $customer_user_details->location_name = ""; //$request->location_name;

        $customer_user_details->save();

        // Commit the transaction
        DB::commit();

        // get the user data
        $data = $this->getUserData($user->id);

        $data = convert_all_elements_to_string($data);

        return response()->success("User created successfully", $data);
    }

    public function apiSocialProfileComplete(Request $request)
    {

        $user = AuthenticateWithSanctum::authenticateGetUser($request);

        if (!$user) {
            return response()->unauthorized();
        }


        // merge request with user id
        $request->merge(['id' => $user->id]);

        return $this->registerUser($request, false, false, true);
    }


    public function apiVerifyOtp(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'access_token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }



            // Get temp user by the access_token
            $tempUser = TempUser::where('access_token', $request->access_token)->first();

            if (!$tempUser) {
                return response()->error("Invalid access token");
            }


            if ($tempUser->user_phone_otp != $request->otp) {

                return response()->error("OTP not matched");
            }


            // Get the user data
            $userData = json_decode($tempUser->user_data);


            // Loadd all data from $userDate to $request
            $request->merge((array)$userData);


            // call registerUser function
            return $this->registerUser($request, false, $tempUser);
        } catch (QueryException $e) {
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    public function apiResendPhoneCode(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'access_token' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }



            // Get temp user by the access_token
            $tempUser = TempUser::where('access_token', $request->access_token)->first();

            if (!$tempUser) {
                return response()->error("Invalid access token");
            }


            // Generate and update otp
            $this->generateOtpUser($tempUser);

            $tempUser->save();

            return response()->success("OTP sent successfully");
        } catch (QueryException $e) {
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    public function apiForgetPassword(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = User::where('email', $request->email)->first();


            if (!$user) {
                return response()->error("User not found");
            }


            // Generate the forget password otp
            $this->generateOtpUser($user);

            // Generate forget password token
            $user->password_reset_code = bin2hex(random_bytes(16));

            $user->save();

            return response()->success("Password reset otp sent", ['password_reset_code' => $user->password_reset_code]);
        } catch (QueryException $e) {
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    public function apiResetPasswordVerifyOtp(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'password_reset_code' => 'required|string',
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = User::select("user_phone_otp")->where('password_reset_code', $request->password_reset_code)->where('user_phone_otp', $request->otp)->first();


            if (!$user) {
                return response()->error("Otp not valid");
            }


            return response()->success("Otp is valid");
        } catch (QueryException $e) {
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    public function apiResendForgetPasswordOtp(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'password_reset_code' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = User::where('password_reset_code', $request->password_reset_code)->first();


            if (!$user) {
                return response()->error("User not found by the password reset code");
            }


            // Generate the forget password otp
            $this->generateOtpUser($user);


            $user->save();

            return response()->success("Password reset otp sent");
        } catch (QueryException $e) {
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }



    public function apiResetPassword(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'password_reset_code' => 'required|string',
                'otp' => 'required',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = User::select("user_phone_otp", "password_reset_code")->where('password_reset_code', $request->password_reset_code)->where('user_phone_otp', $request->otp)->first();


            if (!$user) {
                return response()->error("Otp not valid");
            }


            // Set the new password
            $user->password = bcrypt($request->password);

            // Reset the otp and forget password token
            $user->user_phone_otp = null;
            $user->password_reset_code = "";

            $user->save();


            return response()->success("Password reset successfully");
        } catch (QueryException $e) {
            return response()->error("SQL Error", $e);
        } catch (Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }


    public function apiGetProfile(Request $request)
    {

        try {


            $user = Auth::user();

            $user = User::where('id', $user->id)->first();

            if (!$user) {
                return response()->error("User not found");
            }


            $customer_user_details = CustomerUserDetail::where('user_id', $user->id)->first();

            if (!$customer_user_details) {
                return response()->error("User details not found");
            }

            $data = $user->toArray();
            $data['more_info'] = $customer_user_details->toArray();

            $data = convert_all_elements_to_string($data);

            return response()->success("User found", $data);
        } catch (\Throwable $th) {
            return response()->error("Some Error accured", $th->getMessage());
        }
    }


    public function apiUpdateProfile(Request $request)
    {

        $user = Auth::user();

        // Get the current user id and add to the request so the registerUser function will act as updater
        $request->merge(['id' => $user->id]);

        // Call the register user function which is responsible for the update as well
        return $this->registerUser($request);
    }

    public function apiChangePassword(Request $request)
    {

        // Validate the request old_password
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:8',
        ]);

        // If the validation fails then return the error response
        if ($validator->fails()) {
            return response()->error($validator->errors()->first(), $validator->errors());
        }

        $user = Auth::user();

        // Check if the old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->error("Old password is incorrect");
        }

        // Get the current user id and add to the request so the registerUser function will act as updater
        $request->merge(['id' => $user->id]);

        // Call the register user function which is responsible for the update as well
        return $this->registerUser($request);
    }


    public function apiRequestChangeEmail(Request $request)
    {

        try {

            $user = Auth::user();

            $request->email = strtolower($request->email);

            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users,email',
            ]);

            if ($validator->fails()) {

                // reutrn the first error message
                return response()->error($validator->errors()->first(), $validator->errors());
            }

            $user = User::where('id', $user->id)->first();

            if (!$user) {
                return response()->error("User not found");
            }


            $user->req_chng_email = $request->email;

            $this->generateEmailOtpUser($user);

            $user->save();

            return response()->success("Otp sent to email!");
        } catch (\Throwable $th) {
            return response()->error("Some Error accured", $th->getMessage());
        }
    }

    public function apiVerifyUserOtp(Request $request)
    {

        try {


            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = User::where('id', $user->id)->first();

            if (!$user) {
                return response()->error("User not found");
            }

            if ($user->user_phone_otp != $request->otp) {
                return response()->error("OTP not matched");
            }

            return response()->success("OTP Valid");

            //code...
        } catch (\Throwable $th) {
            return response()->error("Some Error accured", $th->getMessage());
        }
    }

    public function apiChangeEmail(Request $request)
    {

        try {

            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = User::where('id', $user->id)->first();

            if (!$user) {
                return response()->error("User not found");
            }

            if ($user->user_phone_otp != $request->otp) {
                return response()->error("OTP not matched");
            }

            $user->email = $user->req_chng_email;
            $user->req_chng_email = null;
            $user->user_phone_otp = null;

            $user->save();

            $data = $this->getUserData($user->id);

            return response()->success("Email updated successfully", $data);
        } catch (\Throwable $th) {
            return response()->error("Some Error accured", $th->getMessage());
        }
    }


    public function apiRequestPhoneChange(Request $request)
    {

        try {

            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'dial_code' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = User::where('id', $user->id)->first();

            if (!$user) {
                return response()->error("User not found");
            }


            // Check for the phone and dial code if already exists
            $check_exist = User::where('phone', $request->phone)->where('dial_code', $request->dial_code)->get()->toArray();
            if (!empty($check_exist)) {
                return response()->error("Phone already exists");
            }

            $user->req_chng_phone = $request->phone;
            $user->req_chng_dial_code = $request->dial_code;

            $this->generateOtpUser($user);

            $user->save();


            return response()->success("Otp sent to phone!");
        } catch (\Throwable $th) {
            return response()->error("Some Error accured", $th->getMessage());
        }
    }

    public function apiChangePhone(Request $request)
    {

        try {

            $user = Auth::user();

            $validator = Validator::make($request->all(), [
                'otp' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = User::where('id', $user->id)->first();

            if (!$user) {
                return response()->error("User not found");
            }

            if ($user->user_phone_otp != $request->otp) {
                return response()->error("OTP not matched");
            }

            $user->phone = $user->req_chng_phone;
            $user->dial_code = $user->req_chng_dial_code;
            $user->req_chng_phone = null;
            $user->req_chng_dial_code = null;
            $user->user_phone_otp = null;
            $user->phone_verified = 1;
            $user->verified = 1;

            $user->save();

            $data = $this->getUserData($user->id);

            return response()->success("Phone updated successfully", $data);
        } catch (\Throwable $th) {
            return response()->error("Some Error accured", $th->getMessage());
        }
    }

    public function apiTransferWalletAmount(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:1',
                'to_wallet_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation failed", $validator->errors());
            }

            $user = Auth::user();

            // Get the user with the customerUserDetail
            $user = User::where('id', $user->id)->with('customerUserDetail')->first();

            // Get the user by the wallet id
            $toUser = User::whereHas('customerUserDetail', function ($query) use ($request) {
                $query->where('wallet_id', $request->to_wallet_id);
            })->first();

            if (!$toUser) {
                return response()->error("User not found the wallet id");
            }

            if ($user->customerUserDetail->wallet_balance < $request->amount) {
                return response()->error("Insufficient balance");
            }

            // if both user are same then return error
            if ($user->id == $toUser->id) {
                return response()->error("Cannot transfer to same user");
            }

            Db::beginTransaction();


            $user->customerUserDetail->wallet_balance -= $request->amount;
            $toUser->customerUserDetail->wallet_balance += $request->amount;

            $user->customerUserDetail->save();
            $toUser->customerUserDetail->save();


            //  ------ Create the transaction for current user ----
            $currentUserTransaction = new Transaction();
            $currentUserTransaction->customer_id = $user->id;
            $currentUserTransaction->other_customer_id = $toUser->id;
            $currentUserTransaction->transaction_id = Transaction::generateTransactionId();
            $currentUserTransaction->amount = $request->amount;
            $currentUserTransaction->type = Transaction::$type_WalletTransfer;
            $currentUserTransaction->status = Transaction::$payment_status_Success;
            $currentUserTransaction->payment_method = Transaction::$payment_method_Wallet;
            $currentUserTransaction->save();
            // ----------------------------------------------------


            // ----- Create the transaction for the to user -----
            $toUserTransaction = new Transaction();
            $toUserTransaction->customer_id = $toUser->id;
            $toUserTransaction->other_customer_id = $user->id;
            $toUserTransaction->transaction_id = Transaction::generateTransactionId();
            $toUserTransaction->amount = $request->amount;
            $toUserTransaction->type = Transaction::$type_WalletReceive;
            $toUserTransaction->status = Transaction::$payment_status_Success;
            $toUserTransaction->payment_method = Transaction::$payment_method_Wallet;
            $toUserTransaction->save();

            $this->FBNotifyWalletTransfer($toUser, $request->amount, $toUserTransaction->transaction_id);

            // ----------------------------------------------------

            Db::commit();

            return response()->success("Amount transferred successfully");
        } catch (\Exception $e) {

            Db::rollBack();

            return response()->error("Some Error accured", $e->getMessage());
        }
    }

    public function apiGetWalletTransactions(Request $request)
    {

        try {

            $user = Auth::user();

            $limit = $request->limit ? $request->limit : 10;

            $transactions_query = Transaction::orderBy('id', 'desc');

            // Preapre the where query
            Transaction::prepareWalletTransactionsQuery($transactions_query, $user->id);


            $transactions = $transactions_query->paginate($limit);

            $transactions = cleanPaginationResultArray($transactions->toArray());
            $transactions = convert_all_elements_to_string($transactions);


            return response()->success("Transactions found", $transactions);
        } catch (\Exception $e) {
            return response()->error("Some Error accured", $e->getMessage());
        }
    }

    public function apiGetTransactions(Request $request)
    {

        try {

            $user = Auth::user();

            $limit = $request->limit ? $request->limit : 10;

            $transactions_query = Transaction::where('customer_id', $user->id)->where('amount', '>', 0)->orderBy('id', 'desc');


            $transactions = $transactions_query->paginate($limit);

            $transactions = cleanPaginationResultArray($transactions->toArray());

            $transactions = convert_all_elements_to_string($transactions);

            return response()->success("Transactions found", $transactions);
        } catch (\Exception $e) {

            return response()->error("Some Error accured", $e->getMessage());
        }
    }

    public function apiAddCredit(Request $request)
    {

        // Get current logged in user with relation bookings
        $user = Auth::user();

        // Validate request
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->error("Validation error", $validator->messages());
        }


        // Clean old temp transactions
        TempTransaction::cleanOldTransactions();


        try {

            // Generate the stripe payment intent
            $stripeData = PaymentStripe::generatePaymentIntent($request->amount, "User wallet credit", [
                'customer_id' => $user->id,
            ]);
        } catch (\Throwable $th) {
            return response()->error("Error occured while generating payment intent", $th->getMessage());
        }


        // Ready the real transaction array
        $realTransaction = [
            "customer_id" => $user->id,
            "transaction_id" => Transaction::generateTransactionId(),
            "status" => Transaction::$payment_status_Success,
            "type" => Transaction::$type_WalletCredit,
            "payment_method" => Transaction::$payment_method_Stripe,
            "amount" => $request->amount,
            "p_transaction_id" => $stripeData['paymentIntent']->id,
            "p_data" => json_encode([
                'clientSecret' => $stripeData['paymentIntent']->client_secret,
            ]),
        ];


        // Save the temporary transaction
        $transaction = new TempTransaction();
        $transaction->type = TempTransaction::$type_stripe;
        $transaction->p_id = $stripeData['paymentIntent']["id"];
        $transaction->p_status = TempTransaction::$payment_status_Pending;
        $transaction->transaction_data = json_encode($realTransaction);
        $transaction->save();


        return response()->success("Payment intent generated", $stripeData);
    }


    public function apiAddCreditSuccess(Request $request)
    {

        try {


            // Validate the request
            $validator = Validator::make($request->all(), [
                'payment_intent_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->error("Validation error", $validator->messages());
            }

            // Get the temp transaction by the payment intent id
            $tempTransaction = TempTransaction::where('p_id', $request->payment_intent_id)->first();

            if (!$tempTransaction) {
                return response()->error("Transaction not found");
            }

            // Get the transaction data
            $transactionData = json_decode($tempTransaction->transaction_data);


            // Db transaction
            Db::beginTransaction();

            // Increase the wallet balance
            CustomerUserDetail::where('user_id', $transactionData->customer_id)->increment('wallet_balance', $transactionData->amount);

            // Save the real transaction
            $transaction = new Transaction();
            $transaction->fill((array)$transactionData);
            $transaction->save();

            // Delete the temp transaction
            $tempTransaction->delete();


            // Commit the transaction
            Db::commit();


            $newBalance = CustomerUserDetail::where('user_id', $transactionData->customer_id)->value('wallet_balance');


            return response()->success("Amount added successfully", [
                'wallet_balance' => $newBalance
            ]);

            //code...
        } catch (\Throwable $th) {

            // rollback the transaction
            Db::rollBack();

            return response()->error("Some Error accured", $th->getMessage());
        }
    }


    public function apiDeleteAccount(Request $request)
    {

        try {

            $user = Auth::user();

//            $validator = Validator::make($request->all(), [
//                'password' => 'required',
//            ]);
//
//            if ($validator->fails()) {
//                return response()->error("Validation failed", $validator->errors());
//            }
//
//            if (!Hash::check($request->password, $user->password)) {
//                return response()->error("Password not matched");
//            }


            // Delete the user
            $user->delete();


            return response()->success("Account deleted successfully");
        } catch (\Exception $e) {

            // Handle the exception or log the error
            $error = $e->getMessage();

            return response()->error("Some Error accured", $error);
        }
    }


    public function FBNotifyWalletTransfer($customer_obj, $amounReceived, $transaction_id)
    {

        return  prepare_notification($customer_obj, "Wallet Amount received!", "You have received $amounReceived in your wallet", "Wallet Transfer", "wallet_transfer", $transaction_id);
    }



    // Excel reporting
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

            $rows[$key]['dob'] = $val->customerUserDetail->date_of_birth ? VendorUsersController::calculateAge($val->customerUserDetail->date_of_birth) : "N/a";
            $rows[$key]['gender'] = $val->customerUserDetail->gender ? ucfirst($val->customerUserDetail->gender) : "N/A";
            $rows[$key]['artist_rating'] = $val->vendor_to_customer_ratings_avg_rating ? round($val->vendor_to_customer_ratings_avg_rating, 2) : 0;
            $rows[$key]['no_of_bookings'] = $val->customer_user_bookings_count ?? 0;
            $rows[$key]['total_sales'] = $val->customer_user_bookings_sum_total_paid ? round($val->customer_user_bookings_sum_total_paid, 2) : 0;
            $rows[$key]['verified'] = (int)$val->verified ? 'Yes' : 'No';
            $rows[$key]['active'] = (int)$val->active ? 'Yes' : 'No';
            $rows[$key]['artist_ratings_provided'] = VendorRating::where('user_id', $val->id)->get()->count();
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
            "Artist Rating",
            "No of Bookings",
            "Total Sales",
            "Verified",
            "Active",
            "Artist ratings provided",
            "Created Date",
        ];


        // Generate filename
        $file_name = 'customers_' . date('d_m_Y_h_i_s') . '.xlsx';


        return ExcelExporter::exportToExcel($headings, $rows, $file_name);
    }
}
