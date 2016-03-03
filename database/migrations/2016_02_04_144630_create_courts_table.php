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
