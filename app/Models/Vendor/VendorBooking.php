<?php

namespace App\Models\Vendor;

use App\Models\BookingOrder;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\VendorRating;

class VendorBooking extends Model
{

    protected $appends = ['outstanding_amount', 'crnt_stage_outstanding_amount'];
    protected $hidden = ['temp_reschedule_data'];

    // Static order status
    public static $orderStatus = [
        'created' => 'Created',
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'payment' => 'Payment',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }


    // Has many dates
    public function dates()
    {
        return $this->hasMany(VendorBookingDate::class, 'booking_id');
    }

    // Has many dates
    public function all_bookings()
    {
        return $this->hasMany(VendorBooking::class, 'user_id', 'user_id');
    }


     // Has many transactions
     public function transactions()
     {
         return $this->hasMany(Transaction::class, 'order_id')->orderBy('id','desc');
     }


     public function medias()
     {
         return $this->hasMany(VendorBookingMedia::class);
     }

     public function review() {
        return $this->hasOne(VendorRating::class, 'booking_id');
     }


    public static function CalculatePrices($packageOrder)
    {

        $prices = [
            'package_price' => 0,
            'product_price' => 0,
            'addon_price' => 0,
            'grand_total' => 0
        ];


        foreach ($packageOrder->products as $product) {
            $prices["product_price"] += $product->price * $product->qty;
        }

        foreach ($packageOrder->addons as $addon) {
            $prices["addon_price"] += $addon->price * $addon->qty;
        }


        $prices["package_price"] =  $prices["product_price"] + $packageOrder->total;
        $prices["grand_total"] = $prices["package_price"] + $prices["addon_price"] + $packageOrder->tax;

        return $prices;
    }

    public static function GrandTotalAmount($packageOrder)
    {

        $total = $packageOrder->total;
        foreach ($packageOrder->products as $product) {
            $total += $product->price * $product->qty;
        }

        foreach ($packageOrder->addons as $addon) {
            $total += $addon->price * $addon->qty;
        }

        return $total;
    }


    public static function generateUniqueOrderId()
    {
        do {
            $orderId = 'D' . rand(1000000, 9999999);
        } while (VendorBooking::where('order_id', $orderId)->exists());

        return $orderId;
    }

    public static function generateUniqueReferenceNumber()
    {
        do {
            $referenceNumber = 'D' . rand(1000000, 9999999);
        } while (BookingOrder::where('reference_number', $referenceNumber)->exists());

        return $referenceNumber;
    }

    // oustadning_amount
    public function getOutstandingAmountAttribute() {

        return $this->total_with_tax - $this->total_paid;

    }

    // crnt_stage_outstanding_amount
    public function getCrntStageOutstandingAmountAttribute() {


        // If the status is the created then means the current stage pending amount is the advance payment
        if ($this->status == 'created') {

           return [
                "type" => "advance",
                "amount" => $this->advance
            ];

        }


        // We are here it means it's not advance payment so retun outstanding amount
        return [
            "type" => "full",
            "amount" => $this->total_with_tax - $this->total_paid
        ];


    }

    // before_reschedule_dates attribute
    public function getBeforeRescheduleDatesAttribute($value) {

        if (!$value) {return [];}

        return json_decode($value, true);

    }



    public function getTotalHoursAttribute($value) {

        if (is_null($value)) {return null;}

        return $value == intval($value) ? intval($value) : $value;

    }



}
