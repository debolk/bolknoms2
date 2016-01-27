<?php

use App\Models\Meal;
use App\Models\Registration;
use App\Models\User;

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
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('+1 hour'),
        ]);

        $this->post('/aanmelden', [
            'name' => 'Hans van Baalen',
            'email' => 'hans@vvd.nl',
            'handicap' => 'veel vlees',
            'meal_id' => $meal->id,
        ]);

        $this->assertResponseStatus(204);
        $this->seeInDatabase('registrations', [
            'name' => 'Hans van Baalen',
            'email' => 'hans@vvd.nl',
            'handicap' => 'veel vlees',
            'confirmed' => false,
            'meal_id' => $meal->id,
        ]);
    }

    /** @test */
    public function can_register_for_a_named_meal_without_an_account()
    {
        factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('+1 hour'),
        ]);
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 days'),
            'locked_timestamp' => strtotime('+1 days'),
            'event' => 'speciale maaltijd',
        ]);

        $this->post('/aanmelden', [
            'name' => 'Hans van Baalen',
            'email' => 'hans@vvd.nl',
            'handicap' => 'veel vlees',
            'meal_id' => $meal->id,
        ]);

        $this->assertResponseStatus(204);
        $this->seeInDatabase('registrations', [
            'name' => 'Hans van Baalen',
            'email' => 'hans@vvd.nl',
            'handicap' => 'veel vlees',
            'confirmed' => false,
            'meal_id' => $meal->id,
        ]);
    }

    /** @test */
    public function can_register_for_a_meal_with_an_account()
    {
        $user = factory(User::class)->create();
        $this->loginAs($user);
        factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('+1 hour'),
        ]);
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 days'),
            'locked_timestamp' => strtotime('+1 days'),
        ]);

        $this->post('/aanmelden', ['meal_id' => $meal->id]);

        $this->assertResponseStatus(204);
        $this->seeInDatabase('registrations', [
            'name' => $user->name,
            'email' => $user->email,
            'handicap' => $user->handicap,
            'confirmed' => true,
            'meal_id' => $meal->id,
        ]);
    }

    /** @test */
    public function can_deregister_from_a_meal()
    {
        $user = factory(User::class)->create();
        $this->loginAs($user);
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('+1 hour'),
        ]);
        $registration = factory(Registration::class)->make();
        $registration->user()->associate($user);
        $registration->meal()->associate($meal);
        $registration->save();

        $this->post('/afmelden', ['meal_id' => $meal->id]);

        $this->assertResponseStatus(204);
        $this->seeInDatabase('registrations', ['id' => $registration->id]); // Note: soft-deleting
        $this->assertNull(Registration::find($registration->id));
        $this->assertNotNull(Registration::withTrashed()->find($registration->id));
    }

    /** @test */
    public function can_confirm_a_registration()
    {
        $meal = factory(Meal::class)->create([
            'meal_timestamp' => strtotime('+2 hours'),
            'locked_timestamp' => strtotime('+1 hour'),
        ]);
        $registration = factory(Registration::class)->make(['confirmed' => false]);
        $registration->meal()->associate($meal);
        $registration->save();

        $this->visit('/bevestigen/'.$registration->id.'/'.$registration->salt);

        $this->assertResponseOk();
        $this->see('bevestigd');
        $this->seeInDatabase('registrations', ['id' => $registration->id, 'confirmed' => true]);
    }
}
