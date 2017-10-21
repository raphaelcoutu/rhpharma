<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignedShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('shift_id');
            $table->boolean('is_generated'); //si généré par le script VS inscrit par un gestionnaire
            $table->boolean('is_published'); //si est public (donc approuvé par gestionnaire et public)
            $table->date('date');
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
        Schema::dropIfExists('assigned_shifts');
    }
}
