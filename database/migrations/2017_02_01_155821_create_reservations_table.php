<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations',function(Blueprint $table){
           $table->increments('id') ;
           $table->date('dateStart');
           $table->date('dateEnd');
           $table->time('hourStart');
           $table->time('hourEnd');
           $table->integer('fkWho')->unsigned();
           $table->integer('fkWithWho')->unsigned()->nullable();
           $table->integer('fkTypeReservation')->unsigned();
           $table->integer('fkCourt')->unsigned();
           $table->timestamps();
           $table->softDeletes();

           $table->foreign('fkWho')->references('id')->on('personal_informations');
           $table->foreign('fkWithWho')->references('id')->on('personal_informations');
           $table->foreign('fkTypeReservation')->references('id')->on('type_reservations');
           $table->foreign('fkCourt')->references('id')->on('courts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reservations');
    }
}
