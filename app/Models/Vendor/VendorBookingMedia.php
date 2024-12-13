<?php

namespace App\Models\Vendor;

use Illuminate\Database\Eloquent\Model;

class VendorBookingMedia extends Model
{
    public static $mediaFolderName = "artist-booking";

    protected $appends = ['media_url'];

    protected $fillable = ['filename'];

    public function booking()
    {
        return $this->belongsTo(VendorBooking::class);
    }
    

    public function getMediaUrlAttribute()
    {
        return get_uploaded_image_url($this->filename, VendorBookingMedia::$mediaFolderName);

    }

    public static function deleteMedia($filename)
    {
        return deleteFileNew($filename, VendorBookingMedia::$mediaFolderName);
    }
}
