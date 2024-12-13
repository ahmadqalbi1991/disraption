<?php

namespace App\Payments;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentStripe
{

    public static function generatePaymentIntent($amount, $desription, $metadata = [])
    {



        Stripe::setApiKey(config('services.stripe.secret'));

        $amountToPay = ceil($amount) * 100;

        $paymentIntent = PaymentIntent::create([
            'amount' => $amountToPay,
            'currency' => 'AED',
            'description' => $desription,
            'metadata' => $metadata,


        ]);

        return [
            'paymentIntent' => $paymentIntent,
        ];
    }
}
