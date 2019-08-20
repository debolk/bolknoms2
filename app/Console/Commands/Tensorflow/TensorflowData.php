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

    // Memoize cache for looking up meals by their date
    private $mealForCache = [];

    // Output file (will be truncated!)
    private $outputFile = null;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 0);

        // Open the output file, and truncate it
        $this->outputFile = fopen(storage_path('app/public/tensorflow/dataset.csv'), 'w');

        // Print header row in CSV for interpretation
        $this->printRow([
            'timestamp_meal',
            'day_of_week', // int, Monday = 1, Sunday = 7
            'timestamp_sample',
            'minutes_left_until_deadline',
            'count_registrations',
            'c-1', // c is the current meal date, c-1 = yesterday, etc.
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
            'p+0', // p is the same day, last year as per ISO
            'p+1', // p+1 last year tomorrow, etc.
            'p+2',
            'p+3',
            'p+4',
            'p+5',
            'p+6',
            'p+7',
            'result', // final, actual number of registrations
        ]);

        // Pre-process the meals into suitable intermediate variables
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
            $this->debug("pre-processing meal {$meal->id}");

            // Determine sample bounds
            $sample_end = $meal->locked_timestamp->timestamp; // Samples stop here for this meal
            $sample_start = (clone $meal->locked_timestamp)->addDays(-14)->timestamp; // Samples start here for this meal

            // The first few meals are missing their created_at timestamps, so
            // we treat them as a special case for sampling, having only one sample each.
            if ($meal->id <= 44) {
                $sample_start = $sample_end;
            }

            $meal_sample_times[$meal->id] = [$sample_start, $sample_end];
            $min_sample_start = min($min_sample_start, $sample_start);
            $max_sample_end = max($max_sample_end, $sample_end);

            // Calculate the same date as this meal last year, as per ISO
            $year = (int) strftime('%G', $meal->meal_timestamp->timestamp);
            $week = (int) strftime('%V', $meal->meal_timestamp->timestamp);
            $day = (int) strftime('%u', $meal->meal_timestamp->timestamp);
            $lastYearPerISO = (new Carbon)->setISODate($year - 1, $week, $day);

            // Determine the initial result fields of this meal
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

                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-7))->id,          // 'p-7',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-6))->id,          // 'p-6',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-5))->id,          // 'p-5',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-4))->id,          // 'p-4',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-3))->id,          // 'p-3',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-2))->id,          // 'p-2',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(-1))->id,          // 'p-1',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+0))->id,          // 'p+0',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+1))->id,          // 'p+1',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+2))->id,          // 'p+2',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+3))->id,          // 'p+3',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+4))->id,          // 'p+4',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+5))->id,          // 'p+5',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+6))->id,          // 'p+6',
                $this->mealFor($meals, (clone $lastYearPerISO)->addDays(+7))->id,          // 'p+7',
            ];

            foreach ($meal->registrations as $r) {
                if ($r->created_at instanceof Carbon) {
                    $registrations[] = [$r->created_at->timestamp, $meal->id];
                }
            }
        }
        $this->debug("done preprocessing");

        uasort($registrations, function ($a, $b) {
            return $a[0] - $b[0];
        }); // Sort ascending
        $registrations = array_values($registrations);

        $this->debug("start processing samples, end = $max_sample_end");

        // Sample the number of registrations per 15 minutes
        $sampling_step = 15*60;
        $iterator = new RegistrationsIterator($registrations);
        for ($t = $min_sample_start; $t <= $max_sample_end; $t += $sampling_step) {
            $iterator->forwardTo($t);

            foreach ($days as $id => $mealSampleIds) {
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

                foreach ($mealSampleIds as $sampleId) {
                    array_push($row, $iterator->get($sampleId));
                }

                array_push($row, $meal_results[$id]);

                $this->printRow($row);
            }
        }
        $this->debug('done processing samples. Command complete');
    }

    /**
     * Output a formatted line of CSV data
     * @param  array  $values
     */
    private function printRow(array $values)
    {
        $line = implode(",", $values) . "\n";
        fwrite($this->outputFile, $line);
    }

    /**
     * Find a meal for a specific date
     * @param  Collection $meals     collection of all meals
     * @param  Carbon     $timestamp timestamp on the day we seek
     */
    private function mealFor(Collection $meals, Carbon $timestamp)
    {
        // Try the memoized results first
        if (isset($this->mealForCache[$timestamp->timestamp])) {
            return $this->mealForCache[$timestamp->timestamp];
        }

        // Find a meal that is on the same day as the timestamp
        foreach ($meals as $candidate) {
            if ($candidate->meal_timestamp->isSameDay($timestamp)) {
                $this->mealForCache[$timestamp->timestamp] = $candidate;
                return $candidate;
            }
        }

        // Return a fake NullObject Meal if none are found
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
