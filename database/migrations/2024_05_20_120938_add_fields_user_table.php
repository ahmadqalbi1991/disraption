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
    
        Schema::table('users', function (Blueprint $table) {
            $table->string('device_type')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('device_cart_id')->nullable();
            $table->string('password_reset_code')->nullable();
            $table->string('req_chng_email')->nullable();
            $table->string('req_chng_phone')->nullable();
            $table->string('req_chng_dial_code')->nullable();
        });


        // add index for the new fields
        Schema::table('users', function (Blueprint $table) {
            $table->index('fcm_token');
            $table->index('device_cart_id');
            $table->index('forget_pass_token');
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
