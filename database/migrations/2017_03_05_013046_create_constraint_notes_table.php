<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstraintNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraint_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('constraint_id');
            $table->text('content');
            $table->boolean('is_secret_note');
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
        Schema::dropIfExists('constraint_notes');
    }
}
