<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       
        // Booking orders
        Schema::create('booking_orders', function (Blueprint $table) {

            $table->id();

            // add customer id
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');

            // add vendor id
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');

            // add vendor booking id
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')->references('id')->on('vendor_bookings')->onDelete('cascade');

            // add booking reference number
            $table->string('reference_number');

            // status string
            $table->string('status');

            // order total paid
            $table->decimal('total_paid', 10, 2);

            // tax
            $table->decimal('tax', 10, 2);

            // discount
            $table->decimal('discount', 10, 2);

            // Order id
            $table->string('order_id', 50)->nullable();

            // isRescheduled integer 0/1
            $table->integer('is_rescheduled')->default(0);

            // Add timestamps
            $table->timestamps();

            // Add indexes
            $table->index('customer_id');
            $table->index('vendor_id');
            $table->index('booking_id');
            $table->index('reference_number');
            $table->index('order_id');
            $table->index('status');

        });


        // transactions table
        Schema::create('transactions', function (Blueprint $table) {

            $table->id();

            // customer id
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('other_customer_id');
            $table->foreign('other_customer_id')->references('id')->on('users')->onDelete('cascade');

            // vendor id
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');

            // add order id
            $table->unsignedBigInteger('order_id')->nullable();

            // transaction id
            $table->string('transaction_id', 50)->nullable();

            // transaction status
            $table->string('status');

            // transaction amount
            $table->decimal('amount', 10, 2);

            // transaction type
            $table->string('type');

            // payment method
            $table->string('payment_method');

            $table->string('p_trans_id')->nullable();
            $table->string('p_info')->nullable();
            $table->string('p_data')->nullable();

            // Add timestamps
            $table->timestamps();

            // Add indexes
            $table->index('customer_id');
            $table->index('vendor_id');
            $table->index('order_id');
            $table->index('transaction_id');
            $table->index('status');
            $table->index('type');
            $table->index('payment_method');
            $table->index('p_trans_id');

        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
