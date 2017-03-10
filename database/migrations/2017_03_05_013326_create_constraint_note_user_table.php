<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('constraint_note_id');
            $table->integer('user_id');

            $table->primary(['constraint_note_id', 'user_id']);
            $table->index(['user_id', 'constraint_note_id']);
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
