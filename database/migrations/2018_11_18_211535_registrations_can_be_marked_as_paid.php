<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RegistrationsCanBeMarkedAsPaid extends Migration
{
    public function up()
    {
        Schema::table('registrations', function(Blueprint $table){
            $table->timestamp('paid_at')->nullable(true)->default(null);
        });
    }

    public function down()
    {
        Schema::table('registrations', function(Blueprint $table){
            $table->dropColumn('paid_at');
        });
    }
}
