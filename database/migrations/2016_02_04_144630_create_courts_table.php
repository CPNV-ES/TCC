<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateCourtsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('courts', function(Blueprint $table)
			{
				$table->increments('id');
                
                $table->string('name');
                $table->boolean('indor');
				$table->time('start_time');
				$table->time('end_time');
				$table->integer('booking_window_member');
				$table->integer('booking_window_not_member');
				
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
			Schema::drop('courts');
		}

	}
