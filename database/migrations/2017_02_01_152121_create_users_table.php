<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 45)->nullable();
            $table->string('password',255)->nullable();
            $table->integer('active');
            $table->integer('invitRight');
            $table->integer('validated');
            $table->integer('isAdmin');
            $table->integer('isMember');
            $table->integer('isTrainer');
            $table->integer('fkPersonalInformation')->unsigned();
            $table->string('remember_token',255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('fkPersonalInformation')->references('id')->on('personal_informations');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
