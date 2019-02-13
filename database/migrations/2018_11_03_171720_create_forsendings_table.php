<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForsendingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forsendings', function (Blueprint $table) {
            $table->increments('send_id');
            $table->integer('docu_id')->unsigned();
            $table->string('sender');
            $table->integer('receipient_id')->unsigned();
            $table->date('date_deadline');
            $table->integer('routeinfo_id')->unsigned();
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
        Schema::dropIfExists('forsendings');
    }
}
