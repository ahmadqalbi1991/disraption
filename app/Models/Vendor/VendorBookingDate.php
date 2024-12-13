<?php

namespace App\Models\Vendor;

use App\Models\BookingResource;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class VendorBookingDate extends Model
{

    // disable timestamps
    public $timestamps = false;

    // fillable fields
    protected $fillable = ['date', 'booking_id', 'start_time', 'end_time', 'resource_id'];


    public function VendorBooking()
    {
        return $this->belongsTo(VendorBooking::class);
    }

    public function VendorBookingResource()
    {
        return $this->belongsTo(BookingResource::class, 'resource_id');
    }



}
