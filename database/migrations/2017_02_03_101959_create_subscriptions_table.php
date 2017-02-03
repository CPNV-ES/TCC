<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions',function(Blueprint $table){
           $table->increments('id');
           $table->integer('paid')->default(0);
           $table->integer('fkSeason')->unsigned();
           $table->integer('fkTypeSubscription')->unsigned();
           $table->integer('fkUser')->unsigned();
           $table->timestamps();
           $table->softDeletes();
           $table->foreign('fkSeason')->references('id')->on('seasons');
           $table->foreign('fkTypeSubscription')->references('id')->on('type_subscriptions');
           $table->foreign('fkUser')->references('id')->on('users');

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
