<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->foreignId('collectible_id')->nullable()->constrained();
        });
    }

    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn('collectible_id');
        });
    }
};
