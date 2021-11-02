<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->unsignedInteger('branch_id');
            $table->unsignedInteger('workplace_id');
            $table->unsignedInteger('department_type_id')->nullable();
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

            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('workplace_id')->references('id')->on('workplaces');
            $table->foreign('department_type_id')->references('id')->on('department_types');
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
