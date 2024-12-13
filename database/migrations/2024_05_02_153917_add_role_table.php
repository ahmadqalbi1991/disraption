<?php

use App\Models\Role;
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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->integer('status')->default(0);
            $table->integer('is_admin_role')->default(0); // 0=not admin role, 1=admin role
            $table->timestamps();

            // Add soft delete
            $table->softDeletes();


        });


        // Add Super Admin 
        $role = new Role();
        $role->role = 'Super Admin';
        $role->status = 1;
        $role->is_admin_role = 1;
        $role->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
