<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('department_user', function (Blueprint $table) {
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('history')->default(0);
            $table->tinyInteger('planning_long')->default(0);
            $table->tinyInteger('planning_short')->default(0);
            $table->boolean('active')->default(true);

            $table->primary(['department_id', 'user_id']);
            $table->index(['user_id', 'department_id']);

            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('department_user');
    }
}
