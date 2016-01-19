<?php

use App\Models\Meal;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdministrationMealsTest extends TestCase
{
    use WithoutMiddleware;

    public function testViewMeals()
    {
        $meals = factory(Meal::class, 2)->create();

        $this->visit('/administratie/maaltijden')
             ->see(with($meals[0])->date)
             ->see(with($meals[1])->date);
    }

    public function testViewMealDetails()
    {
        $meal = factory(Meal::class)->create();

        $this->visit('/administratie/maaltijden/'.$meal->id)
             ->assertResponseOk();
    }

    public function testCreateAMeal()
    {
        $this->markTestIncomplete();
    }

    public function testUpdateAMeal()
    {
        $this->markTestIncomplete();
    }

    public function testRegisterAUser()
    {
        $this->markTestIncomplete();
    }

    public function testRegisterAUserAfterDeadline()
    {
        $this->markTestIncomplete();
    }

    public function testRegisterAName()
    {
        $this->markTestIncomplete();
    }

    public function testRegisterANameAfterDeadline()
    {
        $this->markTestIncomplete();
    }

    public function testRemoveARegistration()
    {
        $this->markTestIncomplete();
    }

    public function testPrintRegistrationList()
    {
        $this->markTestIncomplete();
    }

    public function testRemoveAMeal()
    {
        $this->markTestIncomplete();
    }
}
