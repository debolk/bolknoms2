<?php

use Illuminate\Database\Migrations\Migration;

class AddUserToRegistration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrations', function ($table) {
            $table->string('username')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrations', function ($table) {
            $table->dropColumn('username');
        });
    }
}
