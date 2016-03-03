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
			Schema::create('reservations', function(Blueprint $table)
			{
				$table->increments('id');
                
                $table->integer('fk_court')->unsigned();
                $table->integer('fk_member_1')->unsigned();
                $table->integer('fk_member_2')->unsigned();
                $table->integer('fk_season')->unsigned();
                
                $table->foreign('fk_court')->references('id')->on('courts');
                $table->foreign('fk_member_1')->references('id')->on('members');
                $table->foreign('fk_member_2')->references('id')->on('members');
                $table->foreign('fk_season')->references('id')->on('seasons');
                
                $table->timestamp('date_hours');
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
			Schema::drop('reservations');
		}

	}
