<?php

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdministrationUsersTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    public function can_see_a_list_of_all_users()
    {
        $users = factory(User::class, 2)->create();

        $this->visit('/administratie/gebruikers')
             ->see(with($users[0])->name)
             ->see(with($users[1])->name);
    }

    /** @test */
    public function can_block_a_user()
    {
        $user = factory(User::class)->create(['blocked' => false]);

        $this->post('/administratie/gebruikers/'.$user->id.'/blokkeren');

        $this->assertResponseOk();
        $this->seeInDatabase('users', ['id' => $user->id, 'blocked' => true]);
    }

    /** @test */
    public function can_unblock_a_blocked_user()
    {
        $user = factory(User::class)->create(['blocked' => true]);

        $this->post('/administratie/gebruikers/'.$user->id.'/vrijgeven');

        $this->assertResponseOk();
        $this->seeInDatabase('users', ['id' => $user->id, 'blocked' => false]);
    }

    /** @test */
    public function can_change_the_handicap_of_a_user()
    {
        $user = factory(User::class)->create(['handicap' => 'geen vlees']);

        $this->post('/administratie/gebruikers/'.$user->id.'/handicap', ['handicap' => 'juist vlees!']);

        $this->assertResponseOk();
        $this->seeInDatabase('users', ['id' => $user->id, 'handicap' => 'juist vlees!']);
    }
}
