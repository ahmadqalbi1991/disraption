<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingResource extends Model
{
    use HasFactory;

    public $timestamps = false;

    public $fillable = [
        'name',
        'active',
        'deleted',
    ];



    // public static function to get the all Resources
    public static function getAll()
    {

        // Retrieve the countries with the specified conditions
        $rows = BookingResource::where(['deleted' => 0, 'active' => 1])->orderByRaw('LOWER(name)')
        ->orderByRaw('LENGTH(name), name ASC')
        ->get();

        return $rows;
    }
}
