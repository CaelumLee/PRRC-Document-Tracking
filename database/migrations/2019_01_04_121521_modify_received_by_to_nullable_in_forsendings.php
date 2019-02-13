<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyReceivedByToNullableInForsendings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forsendings', function (Blueprint $table) {
            $table->integer('receivedBy_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forsendings', function (Blueprint $table) {
            $table->integer('receivedBy_id');
        });
    }
}
