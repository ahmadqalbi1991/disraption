<?php

namespace App\Models\Vendor;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class VendorPortfolio extends Model
{

    // Appned the column
    protected $appends = ['media_url'];


    protected $fillable = ['filename', 'mime', 'type', 'sort_order', 'title', 'description', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function getMediaUrlAttribute()
    {
        return get_uploaded_image_url($this->filename, 'portfolio');
    }

}
