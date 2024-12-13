<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       // Add booking_resources thabe
        Schema::create('booking_resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('deleted')->default(0);
        });


        // add 4 items
        DB::table('booking_resources')->insert([
            ['name' => 'Workstation A'],
            ['name' => 'Workstation B'],
            ['name' => 'Workstation C'],
            ['name' => 'Workstation D'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
