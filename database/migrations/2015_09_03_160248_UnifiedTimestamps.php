<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UnifiedTimestamps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meals', function($table){
            $table->datetime('meal_timestamp');
            $table->datetime('locked_timestamp');
            $table->date('date')->nullable()->change();
            $table->time('mealtime')->nullable()->default(null)->change();
            $table->date('locked_date')->nullable()->change();
            $table->time('locked')->nullable()->change();
        });

        \DB::unprepared("UPDATE meals SET meal_timestamp = CONCAT_WS(' ', date, mealtime);");
        \DB::unprepared("UPDATE meals SET locked_timestamp = CONCAT_WS(' ', locked_date, locked);");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals', function($table){
            $table->dropColumn('meal_timestamp');
            $table->dropColumn('locked_timestamp');
        });
    }
}
