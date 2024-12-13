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

        // Create the rating rable
        Schema::create('vendor_ratings', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedInteger('rating');
            $table->text('review')->nullable();
            $table->timestamps();
        
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Add index
            $table->index('user_id');
            $table->index('vendor_id');

        });

        // Add the vendor_details table total rating column
        Schema::table('vendor_user_details', function (Blueprint $table) {

            $table->decimal('total_rating', 3, 2)->default(0); // Add total_rating column

            // add index
            $table->index('total_rating');

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
