<?php

use App\Models\Meal;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class Mealstest extends TestCase
{
    public function testRationality()
    {
        $this->assertEquals(1 + 1, 2);
    }

    public function testListOpenMeals()
    {
        $this->markTestIncomplete('Test not written');

        // $meal = factory(Meal::class)->create([
        //     'meal_timestamp' => strtotime('in two hours'),
        //     'locked_timestamp' => strtotime('in one hour'),
        // ]);

        // $request = $this->action('GET', 'Api\MealsController@open');

        // $this->assertResponseOk();
        // $request->seeJson(['id' => $meal->id]);
    }
}
