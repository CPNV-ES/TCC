<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('invitations' , function(Blueprint $table){
           $table->increments('id');
           $table->string('firstname',45);
           $table->string('lastname',45);
           $table->integer('paid')->default(0);
           $table->integer('fkInvitationAmount')->unsigned();
           $table->integer('fkReservation')->unsigned();
           $table->timestamps();
           $table->foreign('fkInvitationAmount')->references('id')->on('invitation_amounts');
           $table->foreign('fkReservation')->references('id')->on('reservations');

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
        Schema::drop('invitations');
    }
}
