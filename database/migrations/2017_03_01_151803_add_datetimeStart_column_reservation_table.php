<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatetimeStartColumnReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dateTime('dateTimeStart');
            $table->dropColumn('dateStart');
            $table->dropColumn('dateEnd');
            $table->dropColumn('hourStart');
            $table->dropColumn('hourEnd');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
            $table->dropColumn('dateTimeStart');
            $table->date('dateStart');
            $table->date('dateEnd');
            $table->time('hourStart');
            $table->time('hourEnd');

        });
    }
}
