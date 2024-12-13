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
        Schema::table('vendor_bookings', function (Blueprint $table) {

             // add customer user id
             $table->unsignedBigInteger('customer_id')->nullable();
             $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');

            // status string
            $table->string('status')->nullable();

            // order total paid
            $table->decimal('total_paid', 10, 2)->nullable();

            // tax
            $table->decimal('tax', 10, 2)->nullable();

            // discount
            $table->decimal('discount', 10, 2)->nullable();

            // isRescheduled
            $table->integer('is_rescheduled')->default(0);

            // Hourly rate
            $table->decimal('hourly_rate', 10, 2)->nullable();

            // total with tax
            $table->decimal('total_with_tax', 10, 2)->nullable();

            $table->decimal('total_without_tax', 10, 2)->nullable();


            $table->index('customer_id');
 
            

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
