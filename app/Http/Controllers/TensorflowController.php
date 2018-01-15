<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;
use League\Csv\Writer;

class TensorflowController extends Application
{
    public function index()
    {
        return view('tensorflow');
    }

    public function show()
    {
        ini_set('max_execution_time', 0);

        header('Content-Type: text/csv');
        header('Content-Disposition: inline; filename="bolknoms_tensorflow_'.Carbon::now().'.csv"');

        $headers = [
            'timestamp_meal',
            'day_of_week',
            'timestamp_sample',
            'minutes_left_until_deadline',
            'count_registrations',
            'c-1',
            'c-2',
            'c-3',
            'c-4',
            'c-5',
            'c-6',
            'c-7',
            'p-1',
            'p-2',
            'p-3',
            'p-4',
            'p-5',
            'p-6',
            'p-7',
            'p+0',
            'p+1',
            'p+2',
            'p+3',
            'p+4',
            'p+5',
            'p+6',
            'p+7',
            'result',
        ];
        $this->printRow($headers);

        $meals = Meal::with('registrations')->get();

        $meals->each(function ($meal) use ($meals) {

            $closing = $meal->locked_timestamp;

            // First meal entries have no registrations.created_at timestamps to use
            if ($meal->id <= 44) {
                $buckets = $this->generateBuckets($closing);
            }
            else {
                $buckets = collect([$closing]);
            }

            $buckets->each(function ($bucket) use ($meals, $meal, $closing) {

                $year = (int) strftime('%G', $meal->meal_timestamp->timestamp);
                $week = (int) strftime('%V', $meal->meal_timestamp->timestamp);
                $day = (int) strftime('%u', $meal->meal_timestamp->timestamp);
                $lastYearPerISO = (new Carbon)->setISODate($year - 1, $week, $day);

                $row = [
                    $closing->toISO8601String(),            // 'timestamp_meal',
                    $closing->dayOfWeek,                    // 'day_of_week',
                    $bucket->toISO8601String(),             // 'timestamp_sample',
                    $closing->diffInMinutes($bucket),       // 'minutes_left_until_deadline',
                    $meal->registrationsBefore($bucket),    // 'count_registrations',

                    $this->mealFor($meals, $meal, (clone $meal->meal_timestamp)->addDays(-1))->registrationsBefore($bucket),    // 'c-1',
                    $this->mealFor($meals, $meal, (clone $meal->meal_timestamp)->addDays(-2))->registrationsBefore($bucket),    // 'c-2',
                    $this->mealFor($meals, $meal, (clone $meal->meal_timestamp)->addDays(-3))->registrationsBefore($bucket),    // 'c-3',
                    $this->mealFor($meals, $meal, (clone $meal->meal_timestamp)->addDays(-4))->registrationsBefore($bucket),    // 'c-4',
                    $this->mealFor($meals, $meal, (clone $meal->meal_timestamp)->addDays(-5))->registrationsBefore($bucket),    // 'c-5',
                    $this->mealFor($meals, $meal, (clone $meal->meal_timestamp)->addDays(-6))->registrationsBefore($bucket),    // 'c-6',
                    $this->mealFor($meals, $meal, (clone $meal->meal_timestamp)->addDays(-7))->registrationsBefore($bucket),    // 'c-7',

                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(-7))->registrationsBefore($bucket),    // 'p-7',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(-6))->registrationsBefore($bucket),    // 'p-6',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(-5))->registrationsBefore($bucket),    // 'p-5',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(-4))->registrationsBefore($bucket),    // 'p-4',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(-3))->registrationsBefore($bucket),    // 'p-3',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(-2))->registrationsBefore($bucket),    // 'p-2',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(-1))->registrationsBefore($bucket),    // 'p-1',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(+0))->registrationsBefore($bucket),    // 'p+0',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(+1))->registrationsBefore($bucket),    // 'p+1',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(+2))->registrationsBefore($bucket),    // 'p+2',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(+3))->registrationsBefore($bucket),    // 'p+3',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(+4))->registrationsBefore($bucket),    // 'p+4',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(+5))->registrationsBefore($bucket),    // 'p+5',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(+6))->registrationsBefore($bucket),    // 'p+6',
                    $this->mealFor($meals, $meal, (clone $lastYearPerISO)->addDays(+7))->registrationsBefore($bucket),    // 'p+7',

                    $meal->registrations->count(),  // 'result',
                ];
                $this->printRow($row);

            });

        });
        die; // prevent laravel from messing with response
    }

    public function generateBuckets(Carbon $locked_timestamp)
    {
        $current = (clone $locked_timestamp)->subWeeks(2);

        $data = [];
        while ($current <= $locked_timestamp) {
            $data[] = clone $current;
            $current->addMinutes(15);
        }

        return collect($data);
    }

    private function printRow(array $values)
    {
        echo implode(",", $values) . "\n";
    }

    private function mealFor(Collection $meals, Meal $meal, Carbon $timestamp)
    {
        $targets = $meals->filter(function($meal) use ($timestamp) {
            return $meal->meal_timestamp->isSameDay($timestamp);
        });

        if ($targets->count()) {
            return $targets->first();
        }
        else {
            return new class {
                public function registrationsBefore($bucket) {
                    return 0;
                }
            };
        }
    }
}
