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
        Schema::create('customer_user_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->date('date_of_birth')->nullable();
            $table->string('lattitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('location_name')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->integer(('is_social'))->default(0); // If the user is registered via social media

            // Add the wallet balance column
            $table->decimal('wallet_balance', 10, 2)->default(0.00);
            $table->string('wallet_id', 13)->unique()->nullable();

            $table->timestamps();


            // Add index
            $table->index('user_id');
            $table->index('wallet_id');
  
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
