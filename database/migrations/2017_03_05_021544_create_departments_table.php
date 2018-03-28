<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('branch_id');
            $table->integer('workplace_id');
            $table->string('code', 5);
            $table->tinyInteger('department_type_id')->nullable();
            $table->tinyInteger('bonus_weeks');
            $table->tinyInteger('bonus_pts');
            $table->tinyInteger('malus_weeks');
            $table->tinyInteger('malus_pts');
            $table->tinyInteger('monday_am');
            $table->tinyInteger('monday_pm');
            $table->tinyInteger('tuesday_am');
            $table->tinyInteger('tuesday_pm');
            $table->tinyInteger('wednesday_am');
            $table->tinyInteger('wednesday_pm');
            $table->tinyInteger('thursday_am');
            $table->tinyInteger('thursday_pm');
            $table->tinyInteger('friday_am');
            $table->tinyInteger('friday_pm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
