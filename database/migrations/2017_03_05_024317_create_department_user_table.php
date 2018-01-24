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
            $table->integer('department_id');
            $table->integer('user_id');
            $table->decimal('history', 4, 2)->default(0);
            $table->decimal('planning_long', 4, 2)->default(0);
            $table->decimal('planning_short', 4, 2)->default(0);

            $table->primary(['department_id', 'user_id']);
            $table->index(['user_id', 'department_id']);
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
