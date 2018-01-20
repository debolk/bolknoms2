<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;

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


		// 'Replays' known registrations.
		class RegistrationsIterator
		{
			private $registrations;
			private $currentIndex = 0;
			private $currentTimestamp = 0;
			private $currentCounts = [];

			function __construct($registrations)
			{
				$this->registrations = $registrations;
			}

			function forwardTo($timestamp)
			{
				if ($timestamp < $currentTimestamp) {
					throw new Exception();
				}
				$currentTimestamp = $timestamp;

				while ($currentIndex < count($registrations) && $registrations[$currentIndex][0] <= $timestamp)
				{
					$r = $registrations[$currentIndex];
					$mealId = $r[1];
					if (!isset($currentCounts[$mealId]))
						$currentCounts[$mealId] = 0;

					$currentCounts[$mealId]++;

					$currentIndex++;
				}

				return 0;
			}

			function get($id)
			{
				if(!isset($currentCounts[$id]))
					return 0;
				return $currentCounts[$id];
			}
		}

		$meals = Meal::with('registrations')->get();

		$registrations = [];
		$days = [];
		$day_codes = [];
		$meal_sample_times = [];
		$min_sample_start = time();
		$max_sample_end = 0;

		$meals->each(function ($meal) use ($registrations, $days, $day_codes, $meal_sample_times, $min_sample_start, $max_sample_end) {
			$sample_end = $meal->locked_timestamp; // Samples stop here for this meal
			$sample_start = (clone $closing)->addDays(-14); // Samples start here for this meal
			if ($meal->id <= 44)
				$sample_start = $sample_end;


			$meal_sample_times[$meal->id] = array($sample_start->timestamp, $sample_end->timestamp);

			$year = (int) strftime('%G', $meal->meal_timestamp->timestamp);
			$week = (int) strftime('%V', $meal->meal_timestamp->timestamp);
			$day = (int) strftime('%u', $meal->meal_timestamp->timestamp);
			$day_code = $day + 10 * $week + 10 * 100 * $year; // 2014014

			$days[$day_code] = $meal->id;
			array_push($day_codes, $day_code);

			if ($min_sample_start > $sample_start)
				$min_sample_start = $sample_start;
			if ($max_sample_end < $sample_end)
			    $max_sample_end = $sample_end;

			$meal->registrations->each(function($r) {
				array_push($registrations, array($r->created_at->timestamp, $meal->id));
			});
		});
		uasort($registrations, function($a, $b) { return $a[0] - $b[0]; }); // Sort ascending

		$sample_step = 15 * 60;

		$iterator = new RegistrationsIterator($registrations);

		for($t = $min_sample_start; $t += $sample_step; $t <= $max_sample_end)
		{
			$iterator.forwardTo($t);

			foreach ($days as $code => $id)
			{
				$sample_times = $meal_sample_times[$id];
				if ($t < $sample_times[0] || $t > $sample_times[1])
					continue;


			}
		}


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
