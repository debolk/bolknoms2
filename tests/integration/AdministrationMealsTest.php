<?php

use App\Models\Meal;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdministrationMealsTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function can_view_a_list_of_meals()
    {
        $meals = factory(Meal::class, 2)->create();

        $this->visit('/administratie/maaltijden')
             ->see(with($meals[0])->date)
             ->see(with($meals[1])->date);
    }

    /** @test */
    public function can_view_the_details_of_a_meal()
    {
        $meal = factory(Meal::class)->create();

        $this->visit('/administratie/maaltijden/'.$meal->id)
             ->assertResponseOk();
    }

    /** @test */
    public function can_create_a_new_meal()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_update_the_information_of_a_meal()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_add_a_user_to_a_meal()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_add_a_user_to_a_meal_after_the_deadline()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_add_a_registration_without_an_account()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_add_a_registration_without_an_account_after_the_deadline()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_remove_a_registration()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_print_the_list_of_registrations()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function can_remove_a_meal()
    {
        $this->markTestIncomplete();
    }
}
