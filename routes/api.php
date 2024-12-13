<?php

use App\Http\Controllers\admin\AppBannersController;
use App\Http\Controllers\admin\ContactUsEntryController;
use App\Http\Controllers\admin\CustomerUsersController;
use App\Http\Controllers\admin\PagesController;
use App\Http\Controllers\admin\VendorFavouriteController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


Route::get('/modify-table', function (Request $request) {


    Schema::table('users', function (Blueprint $table) {

        //    // Add the column
        //      $table->string('p_trans_id')->nullable();
        //      $table->string('p_info')->nullable();
        //      $table->string('p_data')->nullable();

       // $table->softDeletes();

    });

    // Update all CustomerUserDetail balance
    //  DB::table('customer_user_details')->update(['wallet_balance' => 500000]);

    // Get CustomerUserDetail all rows which wallet_id is null
    //$users = DB::table('customer_user_details')->whereNull('wallet_id')->get();


    //return VendorBookingController::generatePayment();


});


Route::controller(\App\Http\Controllers\admin\CustomerUsersController::class)->prefix("v1/auth")->name("api.v1.")->group(function () {

    Route::post('/logout', "\App\Http\Controllers\AuthController@logout")->name('logout');
    Route::post("/email_login", "\App\Http\Controllers\AuthController@login");
    Route::post("/email-phone-login", "\App\Http\Controllers\AuthController@login");
    Route::post("/social/login-signup", "apiSocialLoginSignup");
    Route::post("/social/profile", "apiSocialProfileComplete");
    Route::post("/signup", "apiRegisterUser");
    Route::post("/confirm_phone_code", "apiVerifyOtp");
    Route::post("/resend_phone_code", "apiResendPhoneCode");
    Route::post("/forgot_password", "apiForgetPassword");
    Route::post("/resend_forgot_password_otp", "apiResendForgetPasswordOtp");
    Route::post("/reset_password_otp_verify", "apiResetPasswordVerifyOtp");
    Route::post("/reset_password", "apiResetPassword");

});



// Homepage & Masters
Route::namespace('App\Http\Controllers\admin')->prefix("v1")->name("api.v1.")->group(function () {

    Route::get("/home", "VendorUsersController@apiGetHome");
    Route::get("/countries", "CountryController@apiGetCountries");
    Route::post("/categories", "Category@apiGetCategories");
    Route::get("/app-banners", [AppBannersController::class, "apiGetAppBanners"]);
    Route::get("/settings", [PagesController::class, "apiGetSettings"]);
    Route::post("/page", [PagesController::class, "apiGetPage"]);
});


// Artists
Route::namespace('App\Http\Controllers\admin')->prefix("v1/artist")->name("api.v1.")->group(function () {

    Route::post("/all", "VendorUsersController@apiGetAllArtists");
    Route::post("/search", "VendorUsersController@apiSearchArtists");
    Route::post("top-rated", "VendorUsersController@apiGetTopRatedArtists");
    Route::post("/", "VendorUsersController@apiGetArtist");
    Route::post("/portfolio", "VendorUsersController@apiGetVendorPortfolio");
    Route::post("/reviews ", "VendorUsersController@apiGetArtistReviews");
});


// Customer without verified account
Route::namespace('App\Http\Controllers\vendor')->prefix("v1/customer")->middleware(['sanctum.custom_token', 'customer'])->name("api.v1.")->group(function () {

    // Change Email
    Route::post("/profile/request-change-email", [CustomerUsersController::class, "apiRequestChangeEmail"]);
    Route::post("/profile/change-email", [CustomerUsersController::class, "apiChangeEmail"]);

    // Change Phone
    Route::post("/profile/request-change-phone", [CustomerUsersController::class, "apiRequestPhoneChange"]);
    Route::post("/profile/change-phone", [CustomerUsersController::class, "apiChangePhone"]);

    // Verify otp for both email and phone change
    Route::post("/profile/verify-otp", [CustomerUsersController::class, "apiVerifyUserOtp"]);

});


// Customer with verified account
Route::namespace('App\Http\Controllers\vendor')->prefix("v1/customer")->middleware(['sanctum.custom_token', 'customer', 'is_verified'])->name("api.v1.")->group(function () {

    Route::post("/profile", [CustomerUsersController::class, "apiGetProfile"]);
    Route::post("/profile/update", [CustomerUsersController::class, "apiUpdateProfile"]);
    Route::post("/profile/change-password", [CustomerUsersController::class, "apiChangePassword"]); // password change
    // Delete account
    Route::post("/profile/delete", [CustomerUsersController::class, "apiDeleteAccount"]);


    // Transactions 
    Route::post("/transactions", [CustomerUsersController::class, "apiGetTransactions"]);

    
    // Transfer wallet amount
    Route::post("/wallet/transfer", [CustomerUsersController::class, "apiTransferWalletAmount"]);
    Route::post("/wallet/transactions", [CustomerUsersController::class, "apiGetWalletTransactions"]);
    Route::post("/wallet/add-credit", [CustomerUsersController::class, "apiAddCredit"]);
    Route::post("/wallet/add-credit/success", [CustomerUsersController::class, "apiAddCreditSuccess"]);


    Route::post("/bookings/all", "VendorBookingController@apiGetAllBookings");
    Route::post("/bookings/item", "VendorBookingController@apiGetBookingsByIdOrReference");
    Route::post("/bookings/{id}/review/create", "VendorBookingController@apiGiveBookingReview");
    Route::post("/bookings/reschedule", "VendorBookingController@apiRecheduleBookingDates");
    Route::post("/bookings/reschedule/pay", "VendorBookingController@apiRecheduleBookingDatesPay");
    Route::post("/bookings/reschedule/stripe-success", "VendorBookingController@apiRecheduleBookingDatesStripeSuccess");

    Route::post("/bookings/pay", "VendorBookingController@apiBookingPay");
    Route::post("/bookings/stripe/success", "VendorBookingController@apiBookingStripeSuccess");

    // Favourites
    Route::post("/favourites/all", [VendorFavouriteController::class, "apiGetFavourites"]);
    Route::post("/favourites/add", [VendorFavouriteController::class, "apiAddFavourite"]);
    Route::post("/favourites/remove", [VendorFavouriteController::class, "apiRemoveFavourite"]);

    // contact us
    Route::post("/contact-us", [ContactUsEntryController::class, "apiAddEntry"]);
});


// Auth api
Route::post('/register', 'AuthController@register');
