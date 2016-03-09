<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateMembersTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('members', function(Blueprint $table)
			{
				$table->increments('id');

				$table->string('last_name');
				$table->string('first_name');
				$table->string('address');
				$table->string('city');
				$table->string('email')->unique();
				$table->string('phone');
				$table->string('token');
				$table->string('password');
				$table->string('login');
				$table->rememberToken();

				$table->integer('zip_code');
				$table->integer('inscription_date');

				$table->boolean('active');
				$table->boolean('administrator');
				$table->boolean('validate');

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
			Schema::drop('members');
		}

	}
