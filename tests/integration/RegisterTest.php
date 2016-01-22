<?php

use App\Models\Meal;

class RegisterTest extends TestCase
{
    /** @test */
    public function can_view_an_available_meal()
    {
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('+1 hour'),
        ]);

        $request = $this->action('GET', 'Register@index');

        $this->assertResponseOk();
        $this->see( (string) $meal->longDate());
    }

    /** @test */
    public function cannot_view_a_unavailable_meal()
    {
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('-1 hour'),
        ]);

        $request = $this->action('GET', 'Register@index');

        $this->assertResponseOk();
        $this->dontSee( (string) $meal);
    }

    /** @test */
    public function can_register_for_the_next_meal_without_an_account()
    {
        $this->markTestIncomplete();

        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('-1 hour'),
        ]);

        $request = $this->visit('/')
                        ->click('doorgaan zonder Bolkaccount')
                        ->type('Hans van Baalen', 'name')
                        ->type('hans@vvd.nl', 'email')
                        ->click('Aanmelden');

        $this->seeInDatabase('registrations', [
            'name' => 'Hans van Baalen',
            'email' => 'hans@vvd.nl',
            'confirmed' => false,
            'meal_id' => $meal->id,
        ]);
    }

    /** @test */
    public function can_register_for_a_named_meal_without_an_account()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_register_for_a_meal_with_an_account()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_deregister_from_a_meal()
    {
         $this->markTestIncomplete();
    }

    /** @test */
    public function can_confirm_a_registration()
    {
        $this->markTestIncomplete();
    }
}
