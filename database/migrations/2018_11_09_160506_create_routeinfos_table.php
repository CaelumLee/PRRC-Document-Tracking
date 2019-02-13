<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteinfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('edited_by')->unsigned();
            $table->integer('docu_id')->unsigned();
            $table->text('upload_data')->nullable();
            $table->integer('statuscode_id')->unsigned();
            $table->string('remarks');
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
        Schema::dropIfExists('routeinfos');
    }
}
