<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\HearAboutUs;

class TempTransaction extends Model
{

    protected $fillable = [
        'type',
        'p_id',
        'p_status',
        'transaction_data',
    ]; 

    protected $appends = ['debid_credit'];

    // Static types
    public static $type_stripe = 'stripe';

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


    public static function cleanOldTransactions()
    {

        // Remove 2 days old transactions
        $twoDaysOld = now()->subDays(2);
        self::where('created_at', '<', $twoDaysOld)->delete();
        
    }


}
