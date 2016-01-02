<?php

use App\Models\Meal;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class RegisterTest extends TestCase
{
    public function testCanSeeAnOpenMeal()
    {
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('+1 hour'),
        ]);

        $request = $this->action('GET', 'Register@index');

        $this->assertResponseOk();
        $this->see( (string) $meal);
    }

    public function testCannotSeeAClosedMeal()
    {
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('-1 hour'),
        ]);

        $request = $this->action('GET', 'Register@index');

        $this->assertResponseOk();
        $this->dontSee( (string) $meal);
    }
}
