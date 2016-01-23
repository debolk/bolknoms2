<?php

use App\Http\Helpers\OAuth;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class ProfileTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create(['handicap' => 'Heel veel vlees']);
        $this->loginAs($this->user);
    }

    /** @test */
    public function can_view_your_profile()
    {
        $this->visit('/profiel');
        $this->see('Mijn profiel');
        $this->assertResponseOk();
    }

    /** @test */
    public function profile_contains_your_handicap()
    {
        $this->visit('/profiel');
        $this->see($this->user->handicap);
        $this->assertResponseOk();
    }

    /** @test */
    public function can_set_your_handicap()
    {
        $this->post('/handicap', ['handicap' => 'geen vlees meer']);
        $this->assertResponseOk();
        $this->seeInDatabase('users', ['id' => $this->user->id, 'handicap' => 'geen vlees meer']);
    }

    /** @test */
    public function can_view_attended_meals()
    {
        $registration = factory(Registration::class, 'userRegistration')->create();
        $this->loginAs($registration->user);

        $this->visit('/profiel');
        $this->assertResponseOk();
        $this->see($registration->meal->longDate());
    }
}
