<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Vendor\VendorBooking;
use App\Models\Vendor\VendorPortfolio;
use App\Models\VendorRating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use App\Models\Vendor\VendorUserDetail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    // Timestamps
    public $timestamps = true;

    // use softDeletes;


    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // append the column user_image_url
    protected $appends = ['user_image_url', 'firebase_user_key'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'phone',
        'role',
        'role_id',
        'active',
        'verified',
        'user_phone_otp', 
        'password_reset_code',
        'req_chng_email',
        'req_chng_phone',
        'req_chng_dial_code',
        'user_image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'user_phone_otp',
        'password_reset_code'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function update_password($id, $password)
    {
        return DB::table("users")->where("id", '=', $id)->update(['password' => bcrypt($password)]);
    }


    public static function ClearSpecificFcmToken($fcm_token)
    {
        return DB::table("users")->where("fcm_token",'=',$fcm_token)->update(['fcm_token' => null]);
    }


    public function customerUserDetail()
    {
        return $this->belongsTo(CustomerUserDetail::class, 'id', 'user_id');
    }


    // VendorToCustomerRatings
    public function vendorToCustomerRatings()
    {
        return $this->hasMany(CustomerRating::class, 'user_id', 'id');
    }

    // VendorRatings
    public function vendorRatings()
    {
        return $this->hasMany(VendorRating::class, 'vendor_id', 'id')->limit(4);
    }

     // VendorRatings all
     public function customerRatingsToVendor()
     {
         return $this->hasMany(VendorRating::class, 'user_id', 'id');
     }

    // VendorBookings means how many bookings are done for this vendor
    public function vendorBookings()
    {
        return $this->hasMany(VendorBooking::class, 'user_id', 'id');
    }

    // CustomerBookings means how many booking are done for this customer
    public function customerUserBookings() {
        return $this->hasMany(VendorBooking::class, 'customer_id', 'id');
    }

    // CustomerRatings
    public function customerRatings()
    {
        return $this->hasMany(CustomerRating::class, 'user_id', 'id');
    }

     // Update total rating inside customer_user_detials table without need to pass the user id
     public function updateCustomerTotalRatings()
     {
 
         $total_rating = $this->customerRatings()->avg('rating');
         $this->customerUserDetail()->update(['total_rating' => $total_rating]);
 
     }

    // Update total rating inside vendor_details table without need to pass the user id
    public function updateVendorTotalRatings()
    {

        $total_rating = $this->vendorRatings()->avg('rating');
        $this->vendor_details()->update(['total_rating' => $total_rating]);

    }


    // vendor_details
    public function vendor_details()
    {
        return $this->belongsTo(VendorUserDetail::class, 'id', 'user_id');
    }

    // user_role
    public function user_role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }


    public function getUserImageUrlAttribute()
    {

        if (!$this->user_image) {return "";}

        return get_uploaded_image_url($this->user_image, 'vendor_user');
    }


    public function vendorPortfolio()
    {
        return $this->hasMany(VendorPortfolio::class, 'user_id', 'id');
    }

    public function customerBookings()
    {
        return $this->hasMany(VendorBooking::class, 'user_id', 'id');
    }


    public static function generateWalletId() {

        // Generate 11 digits numbers prefixed with Sp
        return "sp" . rand(10000000000, 99999999999);

    }


    public function getFirebaseUserKeyAttribute()
    {
        return "disraption-user-" . $this->id;
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }


    public function getFirstNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getLastNameAttribute($value)
    {
        return ucwords($value);
    }
}
