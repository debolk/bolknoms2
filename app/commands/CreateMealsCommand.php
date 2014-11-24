<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Automatically create meals in the database for this 
 */
class CreateMealsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'meals:generate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Generate the meals for this week and the next';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // Get next monday
        $date = strtotime('next monday');

        // Walk until the thursday (4 days)
        for ($i=0; $i < 4; $i++) { 
            $current_date = date('Y-m-d', strtotime("+{$i} days", $date));
            echo "Attempting {$current_date}...";
            if (Meal::withTrashed()->where('date', '=', $current_date)->count() == 0) {
                Meal::create(['date' => $current_date]);
                echo "created\n";
            }
            else {
                echo "exists\n";
            }
        }
	}
}
