<?php

use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        // Refuse to operate in production environments:
        // there is just no scenario in which this would be a good idea
        if (App::environment() === 'production') {
            echo "Fatal error: even though you said yes, \nI will not allow you to drop the production database \nand seed it with random data\n\nGo away.\n\n";
            exit(1);
        }

        // Drop all tables except for migrations
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $tables = ['users', 'registrations', 'meals', 'sessions'];
        foreach($tables as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create 10 meals with 1-5 registrations each
        factory(Meal::class, 5)->create()->each(function($meal) {
            $registrations = factory(Registration::class, rand(1,20))->make();
            foreach ($registrations as $registration) {
                $meal->registrations()->save($registration);
            }
        });

        // Create 3 users
        factory(User::class, 3)->create();
	}

}
