<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MealsCanHaveCapacity extends Migration
{
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->integer('capacity')->nullable(true)->default(null);
        });
    }

    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn('capacity');
        });
    }
}
