<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\HearAboutUs;
use App\Models\Vendor\VendorBooking;

class BookingOrder extends Model
{

    // Static order status
    public static $orderStatus = [
        'pending' => 'Deposit pending',
        'confirmed' => 'Deposit Paid',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        //'rescheduled' => 'Rescheduled',
    ];

    // Belongs to the customer user
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Belongs to the vendor user
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Belongs to the booking
    public function booking()
    {
        return $this->belongsTo(VendorBooking::class, 'booking_id');
    }

    // Has many transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'order_id');
    }

}
