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
        Schema::create('vendor_booking_media', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->unsignedBigInteger('vendor_booking_id');
            $table->foreign('vendor_booking_id')->references('id')->on('vendor_bookings')->onDelete('cascade');
            $table->timestamps();

            $table->index('vendor_booking_id');
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
