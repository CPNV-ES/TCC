<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateSeasonsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('seasons', function(Blueprint $table)
			{
				$table->increments('id');
                
                $table->timestamp('begin_date');
                $table->timestamp('end_date');
                
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
			Schema::drop('seasons');
		}

	}
