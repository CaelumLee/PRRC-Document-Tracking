<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyConstraintsToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::table('docus', function (Blueprint $table){
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('forsendings', function (Blueprint $table){
            $table->foreign('receipient_id')->references('id')->on('users');
            $table->foreign('docu_id')->references('id')->on('docus');
            $table->foreign('routeinfo_id')->references('id')->on('route_infos');
        });

        Schema::table('route_infos', function (Blueprint $table){
            $table->foreign('edited_by')->references('id')->on('users');
            $table->foreign('docu_id')->references('id')->on('docus');
            $table->foreign('statuscode_id')->references('id')->on('statuscode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
