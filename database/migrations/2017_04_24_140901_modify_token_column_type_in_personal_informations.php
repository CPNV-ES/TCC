<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTokenColumnTypeInPersonalInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('personal_informations', function (Blueprint $table) {
          $table->string('_token',255)->nullable()->change();
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
          $table->date('_token',255)->nullable()->change();
      });
    }
}
