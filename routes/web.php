<?php

use App\Http\Controllers\admin\AppBannersController;
use App\Http\Controllers\admin\ContactUsEntryController;
use App\Http\Controllers\admin\VendorUsersController;
use App\Http\Middleware\IsAdmin;
use App\Models\Categories;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Vendor\VendorBooking;
use App\Models\Vendor\VendorUserDetail;
use Illuminate\Support\Facades\Schema;

Route::get('/clear', function () {
    Artisan::call('optimize');
    Artisan::call('optimize:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    dd('cleared');
});




Route::get('/artist-share-link/{id}', [VendorUsersController::class, 'artistShareLink'])->name('artist.share-link');


Route::get('/admin', 'App\Http\Controllers\admin\LoginController@login')->name('admin.login');
Route::post('admin/check_login', 'App\Http\Controllers\admin\LoginController@check_login')->name('admin.check_login');


// ------------ Admin Routes -------------

Route::namespace('App\Http\Controllers\admin')->prefix('admin')->middleware('admin')->name('admin.')->group(function () {

    Route::get('access-restricted', function () {

        $page_heading = "Access Restricted";
        return view('admin.access_restricted', compact('page_heading'));
    })->name('restricted_page');

    Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('change-password', 'AdminController@changePassword')->name('change.password');
    Route::post('change-password', 'AdminController@changePasswordSave')->name('change.password.save');
    Route::get('logout', 'LoginController@logout')->name('logout');

    Route::post("change-other-admin-pass", 'UsersController@other_admin_changePassword')->name('change_other_admin_password');
    Route::match(array('GET', 'POST'), 'change_password', 'UsersController@change_password')->name("change_password_a");
    Route::match(array('GET', 'POST'), 'change_user_password', 'UsersController@change_user_password');


    // Cms pages
    Route::get('cms_pages', 'PagesController@index')->name('cms_pages');
    Route::get('page/create', 'PagesController@create')->name('cms_pages.add');
    Route::get('page/edit/{id}', 'PagesController@edit')->name('cms_pages.edit');
    Route::post('page/save', 'PagesController@save')->name('cms_pages.save');
    Route::delete('page/delete/{id}', 'PagesController@delete')->name('cms_pages.delete');

    // Master: Country
    Route::resource("country", "CountryController");

     // Master: contact us
    Route::resource("contact_us", ContactUsEntryController::class);


    // Master: Booking Resources
    Route::resource("bookingresource", "BookingResourceController");
    // register delete
    Route::post("bookingresource/delete", "BookingResourceController@delete")->name('bookingresource.delete');


    // Master: Category
    Route::get("category", "Category@index")->name("package.category");
    Route::get("category/create", "Category@create");
    Route::get("category/sort", "Category@sort")->name("category.sort");
    Route::post("category/change_status", "Category@change_status");
    Route::get("category/edit/{id}", "Category@edit");
    Route::post("category/delete", "Category@destroy");
    Route::post("save_category", "Category@store");
    Route::match(array('GET', 'POST'), 'category/sort', 'Category@sort');


    // Settings
    Route::get('settings', 'PagesController@settings')->name('settings');
    Route::post('setting_store', 'PagesController@setting_store')->name('settings.store');

    // Cms: Reschedule Policy
    Route::get('settings/reschedule_policy', 'PagesController@reschedulePolicyView')->name('reschedule_policy.view');
    Route::post('settings/reschedule_policy_save', 'PagesController@rechedulePolicy_store')->name('reschedule_policy.store');

      // Cms: Cancellaton Policy
      Route::get('settings/cancellation-policy', 'PagesController@cancellationView')->name('cancellation.view');
      Route::post('settings/cancellation-policy-save', 'PagesController@cancellation_store')->name('cancellation.store');

    // Cms: Location
    Route::get('settings/location', 'PagesController@locationView')->name('location.view');
    Route::post('settings/location_save', 'PagesController@location_store')->name('location.store');


    // Master: App Banners
    Route::get("app-banners", [AppBannersController::class, 'index'])->name("app_banners.index");
    Route::get("app-banners/create", [AppBannersController::class, 'create'])->name("app_banners.create");
    Route::post("app-banners/change_status", [AppBannersController::class, 'change_status'])->name("app_banners.change_status");
    Route::get("app-banners/edit/{id}", [AppBannersController::class, 'edit'])->name("app_banners.edit");
    Route::post("app-banners/delete", [AppBannersController::class, 'destroy'])->name("app_banners.delete");
    Route::post("save_app_banners", [AppBannersController::class, 'store'])->name("app_banners.save");

    // Users
    Route::get('all', 'AdminUserController@all_users')->name('all');
    // Route::get('customers', 'AdminUserController@customers')->name('customers');

    // Customer User
    Route::get('customers', 'CustomerUsersController@index')->name('customers.index');
    Route::get('customers-delete', 'CustomerUsersController@deleteCustomers')->name('customers.alldelete');
    Route::get('customers/create', 'CustomerUsersController@create')->name('customers.create');
    Route::get('customers/edit/{id}', 'CustomerUsersController@edit')->name('customers.edit');
    Route::post('customers/save', 'CustomerUsersController@store')->name('customers.save');
    Route::post('customers/change_status', 'CustomerUsersController@change_status')->name('customers.change_status');
    Route::post('customers/delete', 'CustomerUsersController@destroy')->name('customers.delete');


    // Vendor users
    Route::get('artist', 'VendorUsersController@index')->name('artist');
    Route::get('artist/create', 'VendorUsersController@create')->name('artist.create');
    Route::get('artist/edit/{id}', 'VendorUsersController@edit')->name('artist.edit');
    Route::post('artist/save', 'VendorUsersController@store')->name('artist.save');
    Route::post('artist/change_status', 'VendorUsersController@change_status')->name('artist.change_status');
    Route::post('artist/delete', 'VendorUsersController@destroy')->name('artist.delete');


    // Admin user
    Route::resource("admin_users", "AdminUserController");
    Route::post("admin_users/change_status", "AdminUserController@change_status")->name('admin_users.change_status');


    // user roles
    Route::get('user_roles/list', 'UserRoleController@index')->name('user_roles.list');
    Route::get('user_roles/create', 'UserRoleController@create')->name('user_roles.create');
    Route::get('user_roles/edit/{id}', 'UserRoleController@create')->name('user_roles.edit');
    Route::post('user_roles/submit', 'UserRoleController@submit')->name('user_roles.submit');
    Route::delete('user_roles/delete/{id}', 'UserRoleController@delete')->name('user_roles.delete');
    Route::post('user_roles/get_role_list', 'UserRoleController@getroleList')->name('getRoleList');
    Route::post('user_roles/status_change/{id}', 'UserRoleController@change_status')->name('user_roles.status_change');

    // Artist Ratings
    Route::get('artist-ratings', 'VendorRatingsController@index')->name('ratings.index');
    Route::get('artist-ratings/create', 'VendorRatingsController@create')->name('ratings.create');
    Route::get('artist-ratings/edit/{id}', 'VendorRatingsController@edit')->name('ratings.edit');
    Route::post('artist-ratings/save', 'VendorRatingsController@store')->name('ratings.save');
    Route::delete('artist-ratings/delete/{id}', 'VendorRatingsController@delete')->name('ratings.delete');


      // Customer Ratings
      Route::get('customer-ratings', 'CustomerRatingsController@index')->name('customer.ratings.index');
      Route::get('customer-ratings/create', 'CustomerRatingsController@create')->name('customer.ratings.create');
      Route::get('customer-ratings/edit/{id}', 'CustomerRatingsController@edit')->name('customer.ratings.edit');
      Route::post('customer-ratings/save', 'CustomerRatingsController@store')->name('customer.ratings.save');
      Route::delete('customer-ratings/delete/{id}', 'CustomerRatingsController@delete')->name('customer.ratings.delete');

    
});


// ------------ Admin/Artist Routes -------------

Route::namespace('App\Http\Controllers\vendor')->prefix('admin')->middleware('admin')->name('admin.')->group(function () {
    Route::get('artist/bookings-delete', 'VendorBookingController@deleteBooking')->name('artist-booking.delete');
    // Artist Portfolio
    Route::get('artist/portfolio/{type}/{user_id}', 'VendorPortfolioController@create')->name('portfolio.create');
    Route::post('artist/portfolio-save/{type}/{user_id}', 'VendorPortfolioController@store')->name('portfolio.save');


    // Artist booking
    Route::get('artist/booking/{type}/{user_id}', 'VendorBookingController@index')->name('artist-booking.index');
    Route::get('artist/booking-create/{type}/{user_id}', 'VendorBookingController@create')->name('artist-booking.create');
    Route::get('artist/booking-edit/{type}/{user_id}/{id}', 'VendorBookingController@edit')->name('artist-booking.edit');
    Route::post('artist/booking-save/{type}/{user_id}', 'VendorBookingController@store')->name('artist-booking.save');
    Route::post('booking-change-status/{type}/{user_id}', 'VendorBookingController@change_status')->name('artist-booking.update-status');
    Route::post('booking-cancel/{type}/{user_id}', 'VendorBookingController@cancelBooking')->name('artist-booking.cancel');
    Route::post('artist/booking_delete_image/{type}/{user_id}', 'VendorBookingController@delete_image')->name('artist-booking.delete_image');
    Route::post('artist/booking-orders-search-user', '\App\Http\Controllers\admin\CustomerUsersController@getUserByEmailOrPhone')->name('artist-booking.search_user');
    Route::post('artist/artist-booking-future-dates', 'VendorBookingController@webGetArtistBookingFutureDates')->name('artist-booking.future_dates');
    Route::get('tax_report/{type}/{user_id}', 'VendorBookingController@taxReport')->name('tax_report.index');
    
});

// -----------------------------------------------



// ----------- Admin/customers Routes -------------

Route::namespace('App\Http\Controllers\admin')->prefix('admin')->middleware('admin')->name('admin.')->group(function () {


    // booking orders
    Route::get('customers/booking-orders/{type}/{user_id}', 'CustomerBookingOrderController@index')->name('booking-orders.index');
    Route::get('customers/booking-orders-view/{type}/{user_id}/{id}', 'CustomerBookingOrderController@view')->name('booking-orders.view');

    // Customer transactions
    Route::get('customers/transactions/{type}/{user_id}', 'CustomerTransactionController@index')->name('transactions.index');
    Route::post('customers/transactions-change-status/{type}/{user_id}', 'CustomerTransactionController@change_status')->name('transactions.update-status');

    // Wallet
    Route::get('customers/wallet/{type}/{user_id}', 'WalletController@index')->name('wallet.index');
    Route::post('customers/wallet/balance_save/{type}/{user_id}', 'WalletController@saveWalletBalance')->name('wallet.balance_save');
    Route::get('customers/transactions/view/{type}/{user_id}/{id}', 'WalletController@transaction_view')->name('wallet.transactions.view');
});


// ------------ Direct Artist Routes -------------

//  Artist Main Routes
Route::get('/artist', 'App\Http\Controllers\vendor\LoginController@login')->name('vendor.login');
Route::post('artist/check_login', 'App\Http\Controllers\vendor\LoginController@check_login')->name('vendor.check_login');
Route::get('/forgot-password', 'App\Http\Controllers\vendor\LoginController@forgotpassword')->name('vendor.forgot');
Route::post('artist/check_user', 'App\Http\Controllers\vendor\LoginController@check_user')->name('vendor.check_user');


Route::namespace('App\Http\Controllers\vendor')->prefix('artist')->middleware('vendor')->name('vendor.')->group(function () {
    
    Route::get('access-restricted', '\App\Http\Controllers\admin\AdminUserController@access_restricted')->name('restricted_page');
    Route::get('logout', 'LoginController@logout')->name('logout');
    
    // If vendor is verified
    Route::middleware('is_vendor_verified')->group(function () {


        Route::get('dashboard', 'DashboardController@dashboard')->name('dashboard');

        // Edit profile
        Route::get('artist/edit/{id}', '\App\Http\Controllers\admin\VendorUsersController@edit')->name('artist.edit');
        Route::post('artist/save', '\App\Http\Controllers\admin\VendorUsersController@store')->name('artist.save');

        // Artist Portfolio
        Route::get('portfolio/{type}/{user_id}', 'VendorPortfolioController@create')->name('portfolio.create');
        Route::post('portfolio-save/{type}/{user_id}', 'VendorPortfolioController@store')->name('portfolio.save');


        // Artist booking
        Route::get('booking/{type}/{user_id}', 'VendorBookingController@index')->name('artist-booking.index');
        Route::get('booking-create/{type}/{user_id}', 'VendorBookingController@create')->name('artist-booking.create');
        Route::get('booking-edit/{type}/{user_id}/{id}', 'VendorBookingController@edit')->name('artist-booking.edit');
        Route::post('booking-save/{type}/{user_id}', 'VendorBookingController@store')->name('artist-booking.save');
        Route::post('booking-change-status/{type}/{user_id}', 'VendorBookingController@change_status')->name('artist-booking.update-status');
        Route::post('booking-cancel/{type}/{user_id}', 'VendorBookingController@cancelBooking')->name('artist-booking.cancel');
        
        
        Route::post('artist/booking_delete_image/{type}/{user_id}', 'VendorBookingController@delete_image')->name('artist-booking.delete_image');
        Route::post('artist/booking-orders-search-user', '\App\Http\Controllers\admin\CustomerUsersController@getUserByEmailOrPhone')->name('artist-booking.search_user');


        // customer booking orders
        Route::get('booking-orders-create/{type}/{user_id}', '\App\Http\Controllers\admin\CustomerBookingOrderController@create')->name('booking-orders.create');
        Route::post('booking-orders-save/{type}/{user_id}', '\App\Http\Controllers\admin\CustomerBookingOrderController@store')->name('booking-orders.save');
        Route::post('booking-orders-change-status/{type}/{user_id}', '\App\Http\Controllers\admin\CustomerBookingOrderController@change_status')->name('booking-orders.update-status');
        


        // Customer transactions
        Route::post('customers/transactions-change-status/{type}/{user_id}', '\App\Http\Controllers\admin\CustomerTransactionController@change_status')->name('transactions.update-status');
    });
});

// -----------------------------------------------


Route::get('/', function () {
    return view('welcome');
});
