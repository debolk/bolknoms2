<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ClosingDatesOnASpecificDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meals', function ($table) {
            $table->date('locked_date');
        });

        DB::unprepared('UPDATE meals SET locked_date = date;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals', function ($table) {
            $table->dropColumn('locked_date');
        });
    }
}
