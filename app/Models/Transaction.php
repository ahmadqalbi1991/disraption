<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\HearAboutUs;

class Transaction extends Model
{

    protected $fillable = [
        'customer_id',
        'other_customer_id',
        'vendor_id',
        'order_id',
        'transaction_id',
        'status',
        'type',
        'payment_method',
        'amount',
        'p_trans_id',
        'p_info',
        'p_data',
    ]; 

    protected $hidden = ['p_data'];

    protected $appends = ['debid_credit', 'app_label'];

    // Static types
    public static $type_WalletCredit = 'wallet_credit';
    public static $type_WalletTransfer = 'wallet_transfer';
    public static $type_WalletReceive = 'wallet_receive';
    public static $type_Advance = 'booking_advance';
    public static $type_Partial = 'booking_partial';
    public static $type_Full = 'booking_full';
    public static $type_Reschedule = 'booking_reschedule';
    public static $types = [
        'wallet_credit' => 'Wallet Credit',
        'wallet_transfer' => 'Wallet Transfer',
        'wallet_receive' => 'Wallet Receive',
        'booking_advance' => 'Booking Advance Payment',
        'booking_full' => 'Booking Full Payment',
        'booking_partial' => 'Booking Partial Payment',
        'booking_reschedule' => 'Booking Reschedule Paymment',
    ];

    // Static payment methods
    public static $payment_method_Wallet = 'wallet';
    public static $payment_method_Stripe = 'stripe';
    public static $payment_method_StripeApple = 'stripe_apple';
    public static $payment_method_StripeGoogle = 'stripe_google';
    public static $payment_method_StripeCard = 'stripe_card';
    public static $payment_method_Card = 'card';
    public static $payment_method_Online = 'online';

    // Payment status
    public static $payment_status_Pending = 'pending';
    public static $payment_status_Success = 'success';
    public static $payment_status_Failed = 'failed';
    public static $payment_status_Refunded = 'refunded';
    public static $payment_status_Cancelled = 'cancelled';
    public static $payment_status_Accepted = 'accepted';
    public static $payment_status_Rejected = 'rejected';
    public static $payment_status = [
        'pending' => 'Pending',
        'success' => 'Success',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
        'cancelled' => 'Cancelled',
        'accepted' => 'Accepted',
        'rejected' => 'Rejected',
    ];

    // Belongs to the customer user
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->withTrashed();
    }

    // Belongs to the customer user if the type is not the wallet transfer or wallet receive or wallet_credit
    public function otherCustomer()
    {
        return $this->belongsTo(User::class, 'other_customer_id');
    }

    // Belongs to the vendor user if the type is not the wallet transfer or wallet receive or wallet_credit
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

   // Belongs to the order if the type is not the wallet transfer or wallet receive or wallet_credit
   public function order()
    {
        return $this->belongsTo(BookingOrder::class, 'order_id');
    }

    public function getDebidCreditAttribute()
    {
        if ($this->type == self::$type_WalletTransfer) {
            return 'debit';
        }

        if ($this->type == self::$type_WalletReceive) {
            return 'credit';
        }

        if ($this->type == self::$type_WalletCredit) {
            return 'credit';
        }

        return 'debit';
    }

    public function getAppLabelAttribute()
    {
        if ($this->type == self::$type_WalletTransfer) {
            return 'Transfer Amount ';
        }

        if ($this->type == self::$type_WalletReceive) {
            return 'Receive Amount';
        }

        if ($this->type == self::$type_WalletCredit) {
            return 'Recharge';
        }

        if ($this->type == self::$type_Advance) {
            return 'Advance';
        }

        if ($this->type == self::$type_Partial) {
            return 'Booking Partial Payment';
        }

        if ($this->type == self::$type_Full) {
            return 'Paid';
        }

        if ($this->type == self::$type_Reschedule) {
            return 'Reschedule';
        }

        return $this->type;
    }


    public static function prepareWalletTransactionsQuery($transactionsQueryObj, $userId) {

        $transactionsQueryObj->where(function ($query) use ($userId) {
            $query->where('customer_id', $userId)
            ->where('amount', '>', 0)
                ->where(function ($query) {
                    $query->where('type', self::$type_WalletReceive)
                        ->orWhere('type', self::$type_WalletTransfer)
                        ->orWhere('type', self::$type_WalletCredit);
                });
        });
     
        
        return true;
    
    }


    public static function generateTransactionId()
    {
       return "D" . rand(1000000, 9999999);
    }


}
