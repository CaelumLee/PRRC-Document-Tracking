<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComplexityAndConfidentialityForDocus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('docus',function(Blueprint $table){
            $table->integer('confidentiality');
            $table->string('complexity', 8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docus',function(Blueprint $table){
            $table->dropColumn(['confidentiality','complexity']);
        });
    }
}
