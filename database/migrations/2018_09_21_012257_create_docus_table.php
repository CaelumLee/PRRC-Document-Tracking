<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('department_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('subject');
            $table->string('recipient');
            $table->string('addressee')->nullable();
            $table->string('sender');
            $table->string('sender_add')->nullable();
            $table->date('final_action_date');
            $table->integer('is_rush')->default(0);
            $table->string('iso_code')->nullable();
            $table->string('location', 9);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docus');
    }
}
