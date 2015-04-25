<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SoftDeleteModels extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('meals', function($table){ $table->softDeletes(); });
		Schema::table('registrations', function($table){ $table->softDeletes(); });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('meals', function($table){ $table->dropColumn('deleted_at'); });
		Schema::table('registrations', function($table){ $table->dropColumn('deleted_at'); });
	}

}
