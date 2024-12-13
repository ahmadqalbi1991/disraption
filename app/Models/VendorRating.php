<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class VendorRating extends Model
{
    protected $fillable = ['user_id', 'rating', 'review'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

}
