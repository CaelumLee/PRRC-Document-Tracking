<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSenderAndRecipientOnDocu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docus', function(Blueprint $table){
            $table->dropColumn(['recipient', 'addressee', 'sender', 'sender_add']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docus', function(Blueprint $table){
            $table->string('recipient');
            $table->string('addressee')->nullable();
            $table->string('sender');
            $table->string('sender_add')->nullable();
        });
    }
}
