<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConstraintNoteUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraint_note_user', function (Blueprint $table) {
            $table->unsignedInteger('constraint_note_id');
            $table->unsignedInteger('user_id');

            $table->primary(['constraint_note_id', 'user_id']);
            $table->index(['user_id', 'constraint_note_id']);

            $table->foreign('constraint_note_id')->references('id')->on('constraint_notes');
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
        Schema::dropIfExists('constraint_note_user');
    }
}
