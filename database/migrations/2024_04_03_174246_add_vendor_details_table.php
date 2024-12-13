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
        //
        Schema::create('vendor_user_details', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('username', 50);
            $table->date('date_of_birth')->nullable();
            $table->string('lattitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('location_name')->nullable();
            $table->text('about')->nullable()->change();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('tiktok')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->text('c_policy')->nullable()->change();
            $table->text('r_policy')->nullable()->change();

            $table->string('reference_number');

            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->integer('advance_percent')->default(0);


            $table->date('availability_from')->nullable();


            
            $table->index('availability_from');
            $table->index('username');
            $table->index('reference_number');

            
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
