<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSenderRecipientAndReceivedByForForsendings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forsendings', function(Blueprint $table){
            $table->string('addressee')->nullable();
            $table->string('sender_add')->nullable();
            $table->integer('receivedBy_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forsendings', function(Blueprint $table){
            $table->dropColumn(['addressee', 'sender_add', 'receivedBy_id']);
        });
    }
}
