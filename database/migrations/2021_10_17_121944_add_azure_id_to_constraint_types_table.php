<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAzureIdToConstraintTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('constraint_types', function (Blueprint $table) {
            $table->unsignedInteger('azure_id')
                    ->nullable()
                    ->unique()
                    ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('constraint_types', function (Blueprint $table) {
            $table->dropColumn('azure_id');
        });
    }
}
