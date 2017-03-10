<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConstraintTypeCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('constraint_type_criteria', function (Blueprint $table) {
            $table->integer('constraint_type_id');
            $table->integer('criteria_id');

            $table->primary(['constraint_type_id', 'criteria_id']);
            $table->index(['criteria_id', 'constraint_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('constraint_type_criteria');
    }
}
