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
       
        Schema::create('vendor_booking_dates', function (Blueprint $table) {
            $table->id();

            // add booking id
            $table->unsignedBigInteger('booking_id');
            $table->foreign('booking_id')->references('id')->on('vendor_bookings')->onDelete('cascade');

            $table->date('date')->nullable();
            $table->time('start_time', 50)->nullable();
            $table->time('end_time', 50)->nullable();

            $table->unsignedBigInteger('resource_id');

            // Add indexes for efficient search
            $table->index('booking_id');
            $table->index('date');
            $table->index('start_time');

        });


        // Drop the date column from the vendor_bookings table
        Schema::table('vendor_bookings', function (Blueprint $table) {

            $table->dropColumn('date');
            $table->dropColumn('start_time');
            $table->dropColumn('end_time');
            
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
