<?php

namespace App\Console\Commands;

use App\Models\Meal;
use Illuminate\Console\Command;

class GenerateMeals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meals:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the meals for next week';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get next monday
        $date = strtotime('next monday');

        // Walk until the thursday (4 days)
        for ($i=0; $i < 4; $i++) {
            $current_date = date('Y-m-d', strtotime("+{$i} days", $date));
            echo "Attempting {$current_date}...";
            if (Meal::withTrashed()->whereRaw("DATE(meal_timestamp) = '$current_date'")->count() == 0) {
                Meal::create(['meal_timestamp' => $current_date.' 18:30:00', 'locked_timestamp' => $current_date.' 15:00:00']);
                echo "created\n";
            } else {
                echo "exists\n";
            }
        }
    }
}
