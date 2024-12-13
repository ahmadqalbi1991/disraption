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

class WalletController extends Controller
{
    public function index($type, $user_id)
    {

        // if user id is not equals to the current logged-in user id, then check if it's not admin then redirect to restricted page
        if ($user_id != Auth::id() && Auth::user()->user_type_id !== 1) {
            return redirect()->route('admin.restricted_page');
        }


        $page_heading = "Wallet";

        // Get the wallet balance for the given user from the customer_user_details table
        $userData = CustomerUserDetail::where('user_id', $user_id)->first();

        // If user data is not found then return with error message
        if (!$userData) {
            return redirect()->route('admin.restricted_page');
        }

        // Get the wallet balance
        $wallet_balance = $userData->wallet_balance;

        // Decode the json transactions to object
        $transactions = $this->GetDummyTransactions();


        // @todo add the hardcore pagination to the list
        return view('admin.wallet.list', compact('page_heading', 'wallet_balance', 'transactions', 'user_id', 'type'));
    }


    public function GetDummyTransactions() {

        $transactions_json = '
        [
            {"id":1,"amount":100,"type":"credit","description":"Recharged","created_at":"2021-09-01 12:00:00","transaction_id":3456789012},
            {"id":2,"amount":50,"type":"debit","description":"Used for booking","created_at":"2021-09-02 12:00:00","transaction_id":9876543210},
            {"id":3,"amount":200,"type":"credit","description":"Recharged","created_at":"2021-09-03 12:00:00","transaction_id":1234567890},
            {"id":4,"amount":150,"type":"debit","description":"Used for booking","created_at":"2021-09-04 12:00:00","transaction_id":4567890123},
            {"id":5,"amount":300,"type":"credit","description":"Recharged","created_at":"2021-09-05 12:00:00","transaction_id":7890123456},
            {"id":6,"amount":250,"type":"debit","description":"Used for booking","created_at":"2021-09-06 12:00:00","transaction_id":2345678901},
            {"id":7,"amount":400,"type":"credit","description":"Recharged","created_at":"2021-09-07 12:00:00","transaction_id":5678901234},
            {"id":8,"amount":350,"type":"debit","description":"Used for booking","created_at":"2021-09-08 12:00:00","transaction_id":9012345678},
            {"id":9,"amount":500,"type":"credit","description":"Recharged","created_at":"2021-09-09 12:00:00","transaction_id":1234567890},
            {"id":10,"amount":450,"type":"debit","description":"Used for booking","created_at":"2021-09-10 12:00:00","transaction_id":5678901234}
        ]';

        // Decode the json transactions to object
        return json_decode($transactions_json);

    }


    public function create()
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }


    public function transaction_view($type, $user_id, $id) {


        $page_heading = "Transaction";
        $transactions = $this->GetDummyTransactions();

        $transaction = null;

        foreach ($transactions as $trans) {
            if ($trans->id == $id) {
                $transaction = $trans;
                break;
            }
        }

        if (!$transaction) {
            return redirect()->route('admin.restricted_page');
        }

    

        return view('admin.wallet.transaction.list', compact('transaction', 'user_id', 'type', 'page_heading'));

    }


    public function saveWalletBalance(Request $request)
    {

        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };


        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'wallet_balance' => 'required',
        ]);

        if ($validator->fails()) {
            return $returnResponse(0, "Validation failed", formatReqErrors($validator->errors()));
        }

        $userId = $request->user_id;

        // if the supplied request user_id is not the same as the logged-in user's id then check if the logged-in user is an admin
        if ($userId != auth()->id()) {
            $isAdmin = Auth::user()->user_type_id === 1;
            if (!$isAdmin) {
                $status = "0";
                $message = "Unauthorized access";
                return $returnResponse($status, $message);
            }
        }

        // get the customer user details
        $customer_user_details = CustomerUserDetail::where('user_id', $userId)->first();

        // if the customer user details is not found then return with error message
        if (!$customer_user_details) {
            return $returnResponse(0, "User not found");
        }

        // update the wallet balance
        $customer_user_details->wallet_balance = $request->wallet_balance;

        // save the customer user details
        $customer_user_details->save();

        return $returnResponse(1, "Wallet balance updated successfully");
    }


    // create the store function
    public function store(Request $request)
    {


        $returnResponse = function ($status, $message, $errors = [], $data = null) {
            return json_encode(['status' => $status, 'message' => $message, 'errors' => $errors, 'data' => $data]);
        };


        // Validate the request with the following fields as required, first_name, last_name, email, phone, location_name, lattitude, longitude
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'location_name' => 'required',
            'location' => 'required',
            'date_of_birth' => 'required',
            'gender' => 'required',
        ]);


        if ($validator->fails()) {
            // return with  $returnResponse
            return $returnResponse(0, "Validation failed", formatReqErrors($validator->errors()));
        }


        // if id is not provided then validate the password field
        if (!$request->id) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'is_social' => 'required',
            ]);

            if ($validator->fails()) {

                // return with  $returnResponse
                return $returnResponse(0, "Validation failed", formatReqErrors($validator->errors()));
            }


            // If is_social is 0 then need to validate the password
            if ($request->is_social == 0) {
                $validator = Validator::make($request->all(), [
                    'password' => 'required',
                ]);

                if ($validator->fails()) {
                    // return with  $returnResponse
                    return $returnResponse(0, "Validation failed", formatReqErrors($validator->errors()));
                }
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


        // Find or crete the user based on the id
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
            $user->user_type_id = 2;
            $user->email = $request->email;
        }


        // If the is_social is 1 then generate uid password and set the password
        if ($request->is_social == 1 && !property_exists($user, 'password')) {
            $randomString = bin2hex(random_bytes(8));
            $user->password = bcrypt($randomString);
        }


        // check if the email already exists in the database
        if ($request->email != $user->email) {
            $check_exist = User::where('email', $request->email)->get()->toArray();
            if (!empty($check_exist)) {
                return $returnResponse(0, "Email already exists");
            }
        }


        // check if the phone and dial code is already exists in the database
        // If request and user phone is not same then check if the phone exists
        if ($request->phone != $user->phone && $request->dial_code != $user->dial_code) {
            $check_exist = User::where('phone', $request->phone)->where('dial_code', $request->dial_code)->get()->toArray();
            if (!empty($check_exist)) {
                return $returnResponse(0, "Phone already exists");
            }
        }


        // Concate the first name and last name to name
        $user->name = $request->first_name . " " . $request->last_name;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->dial_code = $request->dial_code;


        // if password is provided then set the password
        if ($request->password) {
            $user->password = bcrypt($request->password);
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
        $customer_user_details->lattitude = $lat;
        $customer_user_details->longitude = $long;
        $customer_user_details->location_name = $request->location_name;
        $customer_user_details->date_of_birth = $request->date_of_birth;
        $customer_user_details->gender = $request->gender;


        // if id is not provided then set the is_social from the request
        if (!$request->id) {
            $customer_user_details->is_social = $request->is_social;
        }


        // Save the Customer user details
        $customer_user_details->save();


        // Message based on the id provided it's update else vendor added successfully
        $message = "Customer added successfully";
        if ($request->id) {
            $message = "Customer updated successfully";
        }

        return $returnResponse(1, $message);
    }
}
