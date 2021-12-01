<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToResourceAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resource_accesses', function (Blueprint $table) {
            $table->text('example')->nullable();
            $table->boolean('isOmniusDoc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resource_accesses', function (Blueprint $table) {
            $table->dropColumn('example');
            $table->dropColumn('isOmniusDoc');
        });
    }
}
