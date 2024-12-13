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
        Schema::create('favourites', function (Blueprint $table) {
          
            // id
            $table->id();

            // vendor_id
            $table->unsignedBigInteger('vendor_id')->nullable();

            // customer_id
            $table->unsignedBigInteger('customer_id')->nullable();

            // timepstamp
            $table->timestamps();

            $table->index('vendor_id');
            $table->index('customer_id');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favourite', function (Blueprint $table) {
            //
        });
    }
};
