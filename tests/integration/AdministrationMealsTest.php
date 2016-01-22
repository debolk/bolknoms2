<?php

use App\Models\Meal;

class AdministrationMealsTest extends TestCase
{
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
        $meal = factory(Meal::class)->make([
            'meal_timestamp' => strtotime('+3 hours'),
            'locked_timestamp' => strtotime('+2 hours'),
            'event' => 'test feestje!'
        ]);

        $this->visit('/administratie/maaltijden/')
             ->click('Nieuwe maaltijd toevoegen')
             ->type($meal->meal_timestamp->format('d-m-Y H:i'), 'meal_timestamp')
             ->type($meal->locked_timestamp->format('d-m-Y H:i'), 'locked_timestamp')
             ->type($meal->event, 'event')
             ->press('Maaltijd toevoegen');

        $this->assertResponseOk();
        $this->see('Maaltijd toegevoegd');
        $this->seeInDatabase('meals', [
            'meal_timestamp' => $meal->meal_timestamp->format('Y-m-d H:i'),
            'locked_timestamp' => $meal->locked_timestamp->format('Y-m-d H:i'),
            'event' => $meal->event,
        ]);
    }

    /** @test */
    public function can_update_the_information_of_a_meal()
    {
        $meal = factory(Meal::class)->create();

        $date = strtotime('+2 days');
        $lock = strtotime('+1 days');
        $event = 'nieuwe omschrijving van de maaltijd';

        $this->visit('/administratie/maaltijden/'.$meal->id)
             ->click('maaltijd bewerken')
             ->type(strftime('%d-%m-%Y %H:%M', $date), 'meal_timestamp')
             ->type(strftime('%d-%m-%Y %H:%M', $lock), 'locked_timestamp')
             ->type($event, 'event')
             ->press('Wijzigingen opslaan');

        $this->assertResponseOk();
        $this->see('Maaltijd bijgewerkt');
        $this->seeInDatabase('meals', [
            'meal_timestamp' => strftime('%Y-%m-%d %H:%M', $date),
            'locked_timestamp' => strftime('%Y-%m-%d %H:%M', $lock),
            'event' => $event,
        ]);
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
        $meal = factory(Meal::class)->create();

        $this->visit('/administratie/maaltijden/')
             ->click('Verwijderen');

        $this->assertResponseOk();
        $this->see('verwijderd');
        $this->seeInDatabase('meals', ['id' => $meal->id]); // Note: soft-deleting

        $this->assertNull(Meal::find($meal->id));
        $this->assertNotNull(Meal::withTrashed()->find($meal->id));
    }
}
