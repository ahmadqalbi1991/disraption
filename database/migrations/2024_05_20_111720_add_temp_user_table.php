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
        Schema::create('temp_users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('phone')->unique();
            $table->integer('user_type_id')->nullable(); // Adjust the position as needed
            $table->string('user_phone_otp')->nullable();
            $table->string('access_token')->string();
            $table->json('user_data')->nullable();
            $table->timestamps();

            // add index access_token
            $table->index('access_token');
            $table->index('email');
            $table->index('dial_code');
            $table->index('phone');

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
