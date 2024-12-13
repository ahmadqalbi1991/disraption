<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryModel extends Model
{
    use HasFactory;
    protected $table = "country";
    public $timestamps = false;

    public $fillable = [
        'name',
        'prefix',
        'active',
        'deleted',
        'created_at',
        'updated_at',
        'dial_code',
    ];



    // public static function to get the all countries list sort by dial_code and first dial_code should be 971 if it's available
    public static function getCountries()
    {

        // Retrieve the countries with the specified conditions
        $countries = CountryModel::where(['deleted' => 0, 'active' => 1])->orderBy('dial_code', 'asc')->get();

        // Extract the country with dial code '971'
        $country971 = $countries->firstWhere('dial_code', '971');

        // Filter out the country with dial code '971' from the original collection
        $countries = $countries->filter(function ($country) {
            return $country->dial_code != '971';
        });

        // Add the country with dial code '971' to the beginning of the collection
        if ($country971) {
            $countries->prepend($country971);
        }

        return $countries;
    }
}
