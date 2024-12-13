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
         // transactions table
         Schema::create('temp_transactions', function (Blueprint $table) {

            $table->id();
            $table->string('type', 50)->nullable();
            $table->string('p_id', 100)->nullable();
            $table->string('p_status', 50)->nullable();
            $table->text("transaction_data")->nullable();

            $table->timestamps();

            $table->index('p_id');
            $table->index('type');

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
