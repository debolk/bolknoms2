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

    public function testRegisterToAMealWithoutAccount()
    {
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('-1 hour'),
        ]);

        $request = $this->visit('/')
                        ->click('doorgaan zonder Bolkaccount')
                        ->type('Hans van Baalen', 'name')
                        ->type('hans@vvd.nl', 'email')
                        ->click('Aanmelden');

        $this->assertEquals($meal->registrations()->count(), 1);
    }

    public function testRegisterToANamedMealWithoutAccount()
    {
        $this->markTestIncomplete();
    }

    public function testRegisterToAMealWithAccount()
    {
        $this->markTestIncomplete();
    }

    public function testDeregisterFromAMeal()
    {
         $this->markTestIncomplete();
    }

    public function testConfirmARegistration()
    {
        $this->markTestIncomplete();
    }
}
