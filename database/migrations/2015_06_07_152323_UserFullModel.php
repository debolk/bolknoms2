<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class UserFullModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('name');
            $table->timestamp('deleted_at')->nullable()->default(null);
        });

        Schema::table('registrations', function ($table) {
            $table->integer('user_id')->unsigned();
        });

        // Add IDs to the registrations table
        DB::unprepared('UPDATE users,registrations
                    SET registrations.user_id = users.id
                    WHERE users.username = registrations.username;');

        // Update name in users table
        DB::unprepared('UPDATE users,registrations
                    SET users.name = registrations.name
                    WHERE users.id = registrations.user_id;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('name');
            $table->dropColumn('deleted_at');
        });

        Schema::table('registrations', function ($table) {
            $table->dropColumn('user_id');
        });
    }
}
