<?php

use App\Models\Meal;

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

    public function testRegisterToAMeal()
    {
         $this->markTestIncomplete('Test not written');
    }

    public function testDeregisterFromAMeal()
    {
         $this->markTestIncomplete('Test not written');
    }

    public function testConfirmARegistration()
    {
        $this->markTestIncomplete('Test not written');
    }
}
