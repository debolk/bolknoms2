<?php

namespace App\Console\Commands;

use App\Models\Meal;
use App\Models\Vacation;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateMeals extends Command
{
    protected $signature = 'meals:generate';
    protected $description = 'Generate the meals for next week';

    public function handle(): int
    {
        $date = Carbon::parse('next sunday');

        for ($i = 0; $i < 4; $i++) {
            $date = $date->addDay();
            $this->createMeal($date);
        }

        return 0;
    }

    private function createMeal(CarbonInterface $date): void
    {
        $dateString = $date->format('Y-m-d');

        // Do not recreate meals that already exist, or where previously deleted
        $hadMeal = (Meal::withTrashed()->whereRaw("DATE(meal_timestamp) = '{$dateString}'")->count() > 0);
        if ($hadMeal) {
            Log::info("Not creating meal for {$dateString}: meal exists or existed previously");
            return;
        }

        // Do not create meals in defined vacation periods
        if (Vacation::inPlannedVacation($date)) {
            Log::info("Not creating meal for {$dateString}: date is in vacation period");
            return;
        }

        // Create the meal
        Meal::create([
            'meal_timestamp' => $dateString . ' 18:30:00',
            'locked_timestamp' => $dateString . ' 15:00:00',
        ]);
        Log::info("Created automatic meal for {$dateString}");
    }
}
