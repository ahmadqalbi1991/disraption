<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Favourite extends Model
{
    protected $fillable = ['vendor_id', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

}
