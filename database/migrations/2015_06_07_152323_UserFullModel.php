<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserFullModel extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table){
            $table->string('name');
            $table->timestamp('deleted_at')->nullable()->default(null);
        });

        Schema::table('registrations', function($table){
            $table->integer('user_id')->unsigned();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function($table){
            $table->dropColumn('name');
            $table->dropColumn('deleted_at');
        });

        Schema::table('registrations', function($table){
            $table->dropColumn('user_id');
        });
	}

}
