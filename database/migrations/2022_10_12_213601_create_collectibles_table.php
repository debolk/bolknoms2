<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectibles', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->timestamps();
        });

        Schema::create('collectible_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable(false);
            $table->foreignId('collectible_id')->constrained();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['collectible_id', 'user_id']);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('collectible_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collectibles');
        Schema::dropIfExists('collectible_user');
    }
};
