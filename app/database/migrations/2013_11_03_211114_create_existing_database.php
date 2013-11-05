<?php

use Illuminate\Database\Migrations\Migration;

class CreateExistingDatabase extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meals', function($table){
              $table->increments('id');
              $table->date('date');
              $table->time('locked');
              $table->string('event')->nullable();
              $table->boolean('promoted');
              $table->timestamps();
            });
            Schema::create('registrations', function($table){
              $table->increments('id');
              $table->string('name');
              $table->string('handicap')->nullable();
              $table->integer('meal_id')->unsigned();
              $table->foreign('meal_id')->references('id')->on('meals');
              $table->string('email')->nullable();
              $table->string('salt');
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
        Schema::drop('registrations');
        Schema::drop('meals');
	}

}