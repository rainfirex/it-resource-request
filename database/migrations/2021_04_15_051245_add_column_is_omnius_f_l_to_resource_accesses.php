<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsOmniusFLToResourceAccesses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resource_accesses', function (Blueprint $table) {
            $table->boolean('isOmniusFL')->default(0)->after('isAsuse');// ФИЗ
            $table->boolean('isOmniusYUL')->default(0)->after('isOmniusFL'); // ЮЛ
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
            $table->dropColumn('isOmniusFL');
            $table->dropColumn('isOmniusYUL');
        });
    }
}
