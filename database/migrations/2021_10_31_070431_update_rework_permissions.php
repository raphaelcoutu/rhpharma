<?php

use App\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateReworkPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('permission_user');
        Schema::drop('permission_role');
        Schema::drop('permissions');

        Schema::create('permissions', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_role', function(Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->string('permission_code');

            $table->primary(['permission_code', 'role_id']);
            $table->index(['role_id', 'permission_code']);

            $table->foreign('permission_code')->references('code')->on('permissions');
            $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission_role');
        Schema::drop('permissions');

        Schema::create('permissions', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('permission_role', function(Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('permission_id');

            $table->primary(['permission_id', 'role_id']);
            $table->index(['role_id', 'permission_id']);

            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->foreign('role_id')->references('id')->on('roles');
        });


        Schema::create('permission_user', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('user_id');

            $table->primary(['permission_id', 'user_id']);
            $table->index(['user_id', 'permission_id']);

            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->foreign('user_id')->references('id')->on('users');
        });

        $oldPermissions = [
            ['name' => 'ReadBranches'],
            ['name' => 'WriteBranches'],
            ['name' => 'ReadUsers'],
            ['name' => 'WriteUsers'],
            ['name' => 'ReadWorkplaces'],
            ['name' => 'WriteWorkplaces'],
            ['name' => 'ReadDepartments'],
            ['name' => 'WriteDepartments'],
            ['name' => 'ReadRoles'],
            ['name' => 'WriteRoles'],
            ['name' => 'ReadSchedules'],
            ['name' => 'WriteSchedules'],
            ['name' => 'ReadConstraintTypes'],
            ['name' => 'WriteConstraintTypes'],
            ['name' => 'ReadHolidays'],
            ['name' => 'WriteHolidays'],
            ['name' => 'ReadConstraints'],
            ['name' => 'WriteConstraints'],
            ['name' => 'ReadSettings'],
            ['name' => 'WriteSettings'],
        ];

        foreach($oldPermissions as $perm) {
            $p = Permission::create($perm);
        }
    }
}
