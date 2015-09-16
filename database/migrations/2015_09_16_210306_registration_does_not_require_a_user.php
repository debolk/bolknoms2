<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RegistrationDoesNotRequireAUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrations', function($table) {
            $table->integer('user_id')->nullable()->change();
        });
        \DB::unprepared("UPDATE registrations SET user_id = NULL WHERE user_id = 0;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No Laravel migration to do this :/
        \DB::unprepared("UPDATE registrations SET user_id = 0 WHERE user_id IS NULL;");
        \DB::unprepared("ALTER TABLE `registrations` MODIFY `user_id` INTEGER NOT NULL;");
    }
}
