<?php

namespace App\Models\Vendor;

use App\Http\Controllers\admin\PagesController;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\HearAboutUs;

class VendorUserDetail extends Model
{

    // append column
    protected $appends = ['reschedule_policies', 'c_policy', 'share_app_link', 'is_available'];

    // disable timestamps created_at and updated_at
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'logo',
        'date_of_birth',
        'latitude',
        'longitude',
        'location_name',
        'is_company',
        'company_name',
        'gender',
        'account_type',
        'username',
        'family_name',
        'categories'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }


    // reschedule policies
    public function getReschedulePoliciesAttribute()
    {
        $return_policies = null;

        // @Deprecated: As the it's move to global settings
        // if ($this->r_policy) {
        //     try {
        //         $return_policies = json_decode($this->r_policy);
        //     } catch (\Exception $e) {
        //         // Handle the exception here
        //     }
        // }

        $return_policies = PagesController::getReschedulePolicies();


        return $return_policies;
    }

    public function getCPolicyAttribute()
    {
        $c_policy = null;


        $c_policy = PagesController::getCPolicy();

        return $c_policy;
    }

    public function getShareAppLinkAttribute()
    {
       // Get the base url
       $baseUrl = url('/artist-share-link/' . $this->user_id);

        return $baseUrl;
    }

    public function getIsAvailableAttribute()
    {

        if (!$this->availability_from || !$this->availability_to) {
            return 0;
        }

        // Check if the availability_from and availability_to is under the current date, these fields have the date in this format "2024-04-24"
        $current_date = date('Y-m-d');
        $availability_from = $this->availability_from;
        $availability_to = $this->availability_to;

        // Check if the availability_from and availability_to is under the current date
        if ($availability_from <= $current_date && $availability_to >= $current_date) {
           return 1;
        } else {
           return 0;
        }

    }

    public function getLattitudeAttribute()
    {

        // Get the location
        $location = PagesController::getLocation();

        return $location["latitude"];

    }

    public function getLongitudeAttribute()
    {

        // Get the location
        $location = PagesController::getLocation();

        return $location["longitude"];

    }

    public function getLocationNameAttribute()
    {

        // Get the location
        $location = PagesController::getLocation();

        return $location["location_name"];

    }
    public function getCategoriesAttribute()
    {
        // Check if categories is not null and contains a valid string
        if (is_string($this->attributes['categories']) && !empty($this->attributes['categories'])) {
            // Split the categories by commas and return as an array
            return explode(',', $this->attributes['categories']);
        }

        // Return an empty array if no valid categories are found
        return [];
    }

    public function getFormattedCategoriesAttribute()
    {
        // Check if categories is not empty and split by comma
        if (is_string($this->attributes['categories']) && !empty($this->attributes['categories'])) {
            $categoryIds = explode(',', $this->attributes['categories']);
            // Fetch all categories in one query
            $categories = Categories::whereIn('id', $categoryIds)->get();
            // Return an array of capitalized category names
            $categories = $categories->pluck('name')->map(function($name) {
                return ucfirst($name);
            })->toArray();
            return $categories;
        }

        return [];
    }

    // Method to fetch all related categories based on the accessor
    public function fetchCategories()
    {
        // Use the accessor to get the category IDs
        $categoryIds = $this->categories;

        // Return the categories from the Categories model
        return Categories::whereIn('id', $categoryIds)->get();
    }


}
