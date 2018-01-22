<?php

namespace App\Console\Commands\Tensorflow;

use Illuminate\Console\Command;
use App\Models\Meal;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;

class TensorflowData extends Command
{
    protected $signature = 'tensorflow:data';
    protected $description = 'Generate input data set for Tensorflow analysis of registrations';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);

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

        $registrations = [];
        $days = [];
        $meal_sample_times = [];
        $min_sample_start = time();
        $max_sample_end = 0;
        $meal_dates = [];
        $meal_dayofweek = [];
        $meal_results = [];

        foreach ($meals as $meal) {
            $this->debug("processing meal {$meal->id}");

            $sample_end = $meal->locked_timestamp->timestamp; // Samples stop here for this meal
            $sample_start = (clone $meal->locked_timestamp)->addDays(-14)->timestamp; // Samples start here for this meal
            if ($meal->id <= 44)
                $sample_start = $sample_end;


            $meal_sample_times[$meal->id] = array($sample_start, $sample_end);

            // calc iso date of last year
            $year = (int) strftime('%G', $meal->meal_timestamp->timestamp);
            $week = (int) strftime('%V', $meal->meal_timestamp->timestamp);
            $day = (int) strftime('%u', $meal->meal_timestamp->timestamp);
            $lastYearPerISO = (new Carbon)->setISODate($year - 1, $week, $day);
            $meal_dayofweek[$meal->id] = $day;
            $meal_dates[$meal->id] = $meal->meal_timestamp->toISO8601String();

            $meal_results[$meal->id] = $meal->registrations->count();

            $days[$meal->id] = [
                $this->mealFor($meals, (clone $meal->meal_timestamp)->addDays(-1))->id,    // 'c-1',
                $this->mealFor($meals, (clone $meal->meal_timestamp)->addDays(-2))->id,    // 'c-2',
                $this->mealFor($meals, (clone $meal->meal_timestamp)->addDays(-3))->id,    // 'c-3',
                $this->mealFor($meals, (clone $meal->meal_timestamp)->addDays(-4))->id,    // 'c-4',
                $this->mealFor($meals, (clone $meal->meal_timestamp)->addDays(-5))->id,    // 'c-5',
                $this->mealFor($meals, (clone $meal->meal_timestamp)->addDays(-6))->id,    // 'c-6',
                $this->mealFor($meals, (clone $meal->meal_timestamp)->addDays(-7))->id,    // 'c-7',

                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-7))->id,    // 'p-7',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-6))->id,    // 'p-6',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-5))->id,    // 'p-5',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-4))->id,    // 'p-4',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-3))->id,    // 'p-3',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-2))->id,    // 'p-2',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-1))->id,    // 'p-1',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+0))->id,    // 'p+0',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+1))->id,    // 'p+1',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+2))->id,    // 'p+2',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+3))->id,    // 'p+3',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+4))->id,    // 'p+4',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+5))->id,    // 'p+5',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+6))->id,    // 'p+6',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+7))->id,    // 'p+7',
            ];


            if ($min_sample_start > $sample_start)
                $min_sample_start = $sample_start;
            if ($max_sample_end < $sample_end)
                $max_sample_end = $sample_end;

            foreach ($meal->registrations as $r) {
                array_push($registrations, array($r->created_at->timestamp, $meal->id));
            }
        }
        $this->debug("done preprocessing, days size: ".sizeof($days));


        uasort($registrations, function($a, $b) { return $a[0] - $b[0]; }); // Sort ascending
        $registrations = array_values($registrations);

        $sample_step = 15 * 60;

        $iterator = new RegistrationsIterator($registrations);

        $this->debug("start processing samples, end = $max_sample_end");

        for($t = $min_sample_start; $t <= $max_sample_end; $t += $sample_step)
        {
            $iterator->forwardTo($t);

            foreach ($days as $id => $mealSampleIds)
            {
                $sample_times = $meal_sample_times[$id];
                if ($t < $sample_times[0] || $t > $sample_times[1]) {
                    continue;
                }

                $row = [
                    $meal_dates[$id], // timestamp_meal
                    $meal_dayofweek[$id], //day_of_week
                    $sample_times[1] - $t, //minutes_left_until_deadline
                    $iterator->get($id), // c+0
                ];

                foreach($mealSampleIds as $sampleId) {
                    array_push($row, $iterator->get($sampleId));
                }

                array_push($row, $meal_results[$id]);

                $this->printRow($row);
            }
        }
    }

    /**
     * Output a formatted line of CSV data
     * @param  array  $values
     */
    private function printRow(array $values)
    {
        echo implode(",", $values) . "\n";
    }

    /**
     * Find a meal for a specific date
     * @param  Collection $meals     collection of all meals
     * @param  Carbon     $timestamp timestamp on the day we seek
     * @return Meal
     */
    private function mealFor(Collection $meals, Carbon $timestamp)
    {
        if (!isset($this->mealForCache))
            $this->mealForCache = [];
        if (isset($this->mealForCache[$timestamp->timestamp]))
            return $this->mealForCache[$timestamp->timestamp];

        foreach ($meals as $candidate) {
            if ($candidate->meal_timestamp->isSameDay($timestamp)) {
                $this->mealForCache[$timestamp->timestamp] = $candidate;
                return $candidate;
            }
        }

        $result = new class {
            public $id = -1;
        };
        $this->mealForCache[$timestamp->timestamp] = $result;
        return $result;
    }

    /**
     * Output a debug message, separate from normal output
     * @param  string $text
     */
    public function debug(string $text)
    {
        fwrite(STDERR, $text . "\n");
    }
}
