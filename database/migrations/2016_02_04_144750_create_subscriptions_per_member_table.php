<?php

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateSubscriptionsPerMemberTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('subscriptions_per_member', function(Blueprint $table)
			{
				$table->increments('id');

				$table->integer('fk_member')->unsigned();
				$table->integer('fk_season')->unsigned();
                $table->integer('fk_subscription')->unsigned();
                
                $table->foreign('fk_subscription')->references('id')->on('subscriptions');
				$table->foreign('fk_member')->references('id')->on('members');
				$table->foreign('fk_season')->references('id')->on('seasons');
                
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
			Schema::drop('subscriptions_per_member');
		}

	}
