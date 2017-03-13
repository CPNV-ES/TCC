<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaidChargeAmountReservationInTableRemoveInvitationInvitationsAmountTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('reservations', function(Blueprint $table){
            $table->double('chargeAmount', 10, 2);
            $table->integer('paid');
        });
        Schema::table('configs', function(Blueprint $table){
            $table->double('currentAmount');
        });

        Schema::drop('invitations');
        Schema::drop('invitation_amounts');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('reservations' , function(Blueprint $table){
            $table->dropColumn('chargeAmount');
            $table->dropColumn('paid');
        });
        Schema::create('invitation_amounts', function(Blueprint $table)
        {
            $table->increments('id');
            $table->double('amount',10,2);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('invitations' , function(Blueprint $table){
            $table->increments('id');
            $table->string('firstname',45);
            $table->string('lastname',45);
            $table->integer('paid')->default(0);
            $table->integer('fkInvitationAmount')->unsigned();
            $table->integer('fkReservation')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('fkInvitationAmount')->references('id')->on('invitation_amounts');
            $table->foreign('fkReservation')->references('id')->on('reservations');

        });
    }
}
