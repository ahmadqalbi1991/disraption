<?php

namespace App\Http\Controllers;

use App\Models\CustomerUserDetail;
use App\Models\User;
use App\Models\Vendor\VendorUserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        // Validate the request using validator rules
        $validator = validator($request->all(), [
            'email_or_phone' => 'required',
            'password' => 'required'
        ]);

        // If validation fails, return the error messages
        if ($validator->fails()) {
            return response()->validation_error($validator->errors());
        }

        $data = $request->email_or_phone;

        // detect if $request->email_or_phone is email
        $isEmail = filter_var($data, FILTER_VALIDATE_EMAIL) !== false;



        // If email is provided then lowercase
        if ($isEmail) {
            $data = strtolower($data);
        }

        // If phone is provided then remove all spaces
        if (!$isEmail) {
            $data = str_replace(' ', '', $data);

            // Remove plus sign
            $data = str_replace('+', '', $data);

            // Extract the dial code and phone number separated by -
            $phone_parts = explode('-', $data);
            $dial_code = $phone_parts[0];
            $phone = $phone_parts[1];
        }


        // If phone is provided then login by the phone and dial code and password
        if (!$isEmail) {
            $user = User::where('phone', $phone)->where('dial_code', $dial_code)->first();
        } else {
            $user = User::where('email', $data)->first();
        }


        $message = $isEmail ? "Email or password is wrong!" : "Phone or password is wrong!";

        // If user is not found, return unauthorized
        if (!$user) {
            return response()->error($message);
        }

        
        // If user is found, check the password
        if (!Hash::check($request->password, $user->password)) {
            return response()->error($message);
        }


        // If the user is not active
        if ($user->active == 0) {
            return response()->error("User is disabled by the admin!");
        }

        // If the user is not verified
        if ($user->verified == 0) {
            return response()->error("User is not verified!");
        }



        $token = $user->createToken('api')->plainTextToken;
        


        $more_info = null;

        if ((string)$user->user_type_id == "2") {
            $more_info = CustomerUserDetail::where('user_id', $user->id)->first()->toArray();
        }


        // If fcm token is provided, update it in the database
        if ($request->fcm_token) {
            $user->fcm_token = $request->fcm_token;

             // Clear all previous fcm_token
             User::ClearSpecificFcmToken($request->fcm_token);

        }

        // If device type is provided, update it in the database
        if ($request->device_type) {
            $user->device_type = $request->device_type;
        }

        // If device cart id is provided, update it in the database
        if ($request->device_cart_id) {
            $user->device_cart_id = $request->device_cart_id;
        }


        $user->save();


        $data = $user->toArray();
        $data['more_info'] = $more_info;
        $data['access_token'] = $token;


        $data = convert_all_elements_to_string($data);

        return response()->success('Login successful', $data);
    }


    public function logout(Request $request)
    {

        $rules = [
            'access_token' => 'required',
        ];
        $messages = [
            'access_token.required' => trans('validation.access_token_required'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $message = trans('validation.validation_error_occured');
            $errors = $validator->messages();

            return response()->error($message, $errors);
        }

        $tokenParts = explode('|', $request->access_token);
        $tokenId = $tokenParts[0];

        $tokenModel = PersonalAccessToken::find($tokenId);

        if (!$tokenModel) {
            return response()->error('Invalid access token');
        }

        if ($tokenModel) {
            $tokenModel->delete();
            return response()->success('Logout successful');
        }


        return response()->success('Logout successful');
    }
}
