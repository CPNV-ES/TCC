<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyFieldsToNullableIntoPersonalInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal_informations', function (Blueprint $table) {
            $table->string('street',45)->nullable()->change();
            $table->string('streetNbr',45)->nullable()->change();
            $table->string('telephone',45)->nullable()->change();
            $table->string('email',255)->nullable()->change();
            $table->integer('toVerify')->nullable()->change();
            $table->date('birthDate',255)->nullable()->change();
            $table->date('_token',255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('personal_informations', function (Blueprint $table) {
            $table->string('street',45)->change();
            $table->string('streetNbr',45)->change();
            $table->string('telephone',45)->change();
            $table->string('email',255)->change();
            $table->integer('toVerify')->change();
            $table->date('birthDate',255)->change();
            $table->date('_token',255)->change();
        });
    }
}
