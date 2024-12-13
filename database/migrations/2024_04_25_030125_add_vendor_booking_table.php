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
        // PackageOrder
        Schema::create('vendor_bookings', function (Blueprint $table) {
            $table->id();

            // add vendor id
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');


            $table->date('date')->nullable();
            $table->time('start_time', 50)->nullable();
            $table->time('end_time', 50)->nullable();

            $table->string('title');
            $table->string('reference_number');


            // order total
            $table->decimal('total', 10, 2);
            // tax
            $table->decimal('advance', 10, 2);

        
            // payment id
            $table->string('order_id', 50)->nullable();

            $table->decimal('total_hours', 10, 2)->nullable();

            $table->string('last_payment_method')->nullable();

            $table->text('temp_reschedule_data')->nullable();
            $table->text('before_reschedule_dates')->nullable();
            $table->integer('total_rschdl_paid')->default(0);


             // Add timestamps
            $table->timestamps();

            // Add indexes for efficient search
            $table->index('user_id');
            $table->index('reference_number');
            $table->index('order_id');

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
