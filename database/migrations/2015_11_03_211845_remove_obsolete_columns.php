<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveObsoleteColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('locked');
            $table->dropColumn('mealtime');
            $table->dropColumn('locked_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->time('locked')->nullable();
            $table->time('mealtime')->nullable();
            $table->date('locked_date')->nullable();
        });
    }
}
