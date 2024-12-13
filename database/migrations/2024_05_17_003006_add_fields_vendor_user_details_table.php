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
       Schema::table('vendor_user_details', function (Blueprint $table) {

        // add thread string
        $table->string('thread')->nullable()->after('tiktok');

        // availaability to
        $table->date('availability_to')->nullable()->after('availability_from');

        // deposit amount
        $table->decimal('deposit_amount', 8, 2)->nullable()->after('advance_percent');


        $table->index('availability_to');


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
