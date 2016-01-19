<?php

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdministrationUsersTest extends TestCase
{
    use WithoutMiddleware;

    public function testListUsers()
    {
        $users = factory(User::class, 2)->create();

        $this->visit('/administratie/gebruikers')
             ->see(with($users[0])->name)
             ->see(with($users[1])->name);
    }

    public function testBlockUser()
    {
        $this->markTestIncomplete();

        $user = factory(User::class)->create();

        $this->visit('/administratie/gebruikers')
             ->click('Blokkeren');

        $this->seeInDatabase('users', [
            'id' => $user->id,
            'blocked' => true
        ]);
    }

    public function testUnblockUser()
    {
        $this->markTestIncomplete();
    }

    public function testSetHandicap()
    {
        $this->markTestIncomplete();
    }
}
