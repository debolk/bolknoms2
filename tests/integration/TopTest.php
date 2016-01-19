<?php

use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TopTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function can_view_number_of_registrations_in_list()
    {
        /*
         * Create a set of registrations for meals both present and future
         * making the final result look like:
         *
         *        | $very_past_meal | $past_meal | $upcoming_meal |
         * -------|-----------------|------------|----------------|
         * $user1 | registered      | registered | registered     |
         * -------|-----------------|------------|----------------|
         * $user2 | not registered  | registered | not registered |
         * -------|-----------------|------------|----------------|
         *
         * The resulting list should (with the appropriate substitutions)
         * appear like:
         *
         * {$user1->name} (2)
         * {$user2->name} (1)
         */

        $this->user1 = factory(User::class)->create();
        $this->user2 = factory(User::class)->create();

        $this->very_past_meal = factory(Meal::class)->create(['meal_timestamp' => strtotime('-30 days')]);
        $this->past_meal = factory(Meal::class)->create(['meal_timestamp' => strtotime('-2 days')]);
        $this->upcoming_meal = factory(Meal::class)->create(['meal_timestamp' => strtotime('+2 days')]);

        $registration = factory(Registration::class)->make();
        $registration->user()->associate($this->user1);
        $registration->meal()->associate($this->very_past_meal);
        $registration->save();

        $registration = factory(Registration::class)->make();
        $registration->user()->associate($this->user1);
        $registration->meal()->associate($this->past_meal);
        $registration->save();

        $registration = factory(Registration::class)->make();
        $registration->user()->associate($this->user1);
        $registration->meal()->associate($this->upcoming_meal);
        $registration->save();

        $registration = factory(Registration::class)->make();
        $registration->user()->associate($this->user2);
        $registration->meal()->associate($this->past_meal);
        $registration->save();

        // Test output
        $this->visit('/top-eters')
             ->see($this->user1->name . ' (2)')
             ->see($this->user2->name . ' (1)');
    }
}
