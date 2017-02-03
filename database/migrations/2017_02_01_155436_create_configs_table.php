<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs',function(Blueprint $table){
           $table->integer('nbDaysGracePeriod');
           $table->integer('nbDaysLimitNonMember');
           $table->time('courtOpenTime');
           $table->time('courtCloseTime');
           $table->timestamps();
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
        Schema::drop('configs');
    }
}
