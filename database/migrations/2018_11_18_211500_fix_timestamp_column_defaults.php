<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixTimestampColumnDefaults extends Migration
{
    public function up()
    {
        // Turn off strict zero date checking for this session only
        DB::statement("SET @@sql_mode =
                    REPLACE(
                    REPLACE(
                    REPLACE(@@sql_mode, 'NO_ZERO_DATE,', ''),
                                       ',NO_ZERO_DATE', ''),
                                        'NO_ZERO_DATE', '');");

        // Update all tables to have nullable timestamps and consistent data
        collect([
            'meals',
            'registrations',
            'users',
            'vacations',
        ])->each(function(string $tableName) {
            DB::statement("ALTER TABLE {$tableName}
                MODIFY created_at TIMESTAMP NULL DEFAULT NULL,
                MODIFY updated_at TIMESTAMP NULL DEFAULT NULL");

            DB::statement("UPDATE {$tableName} SET created_at = NULL WHERE created_at = '0000-00-00 00:00:00'");
            DB::statement("UPDATE {$tableName} SET updated_at = NULL WHERE updated_at = '0000-00-00 00:00:00'");

        });

        // Re-enable strict dates
        DB::statement("SET @@sql_mode = CONCAT(@@sql_mode, ',', 'NO_ZERO_DATE');");
    }

    public function down()
    {
        throw new \Exception('Irreversible migration: mysql server compatability update');
    }
}
