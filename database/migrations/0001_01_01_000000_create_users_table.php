<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('dial_code')->nullable();
            $table->string('phone')->unique();
            $table->integer('phone_verified')->default(0);
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('role')->nullable();
            $table->integer('verified')->default(0);
            $table->integer('user_type_id')->nullable(); // Adjust the position as needed
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('user_image')->nullable();
            $table->string('user_phone_otp')->nullable();
            $table->integer('active')->default(1);
            $table->rememberToken();
            $table->timestamps();

            // use softDeletes;
            $table->softDeletes();
            
        });

        \DB::table('users')->insert([
            'name'=>'Admin',
            'email'=>'admin@admin.com',
            'dial_code'=>'971',
            'phone'=>'112233445566778899',
            'role'=>'1',
            'user_type_id'=> 1,
            'verified'=> 1,
            'password'=>'$2y$10$4CKClSnfh0w959jNrsJyl.8/oowWbizHIg4FrOlXxfgtYYBU6Y6jK', // Password: Hello@1985
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}


