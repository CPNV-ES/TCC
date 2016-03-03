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
                
                $table->integer('fk_subscriptions')->unsigned();
                
                $table->foreign('fk_subscriptions')->references('id')->on('subscriptions');
                
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
