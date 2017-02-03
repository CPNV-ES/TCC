<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_informations',function(Blueprint $table){
            $table->increments('id');
            $table->string('firstname',45);
            $table->string('lastname',45);
            $table->string('street',45);
            $table->string('streetNbr',45);
            $table->string('telephone',45);
            $table->string('email',255);
            $table->integer('toVerify');
            $table->integer('fkLocality')->unsigned();
            $table->timestamps();

            $table->foreign('fkLocality')->references('id')->on('localities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('personal_informations');
    }
}
