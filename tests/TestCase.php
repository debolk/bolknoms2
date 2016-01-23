<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Session;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * Reset the database after every test
     */
    use DatabaseTransactions;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://bolknoms.app/';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Creates a valid session through faking the Session data
     * @param  User   $user
     * @return void
     */
    protected function loginAs(User $user)
    {
        // $token = new stdClass();
        // $token->access_token = '1234';
        // $token->refresh_token = '1234';
        // $token->expires_at = new DateTime('+1 hours')

        Session::set('oauth', [
            'token' => (object)[
                'access_token' => '1234',
                'refresh_token' => '1234',
                'expires_at' => new DateTime('+1 hours'),
            ],
            'current_user' => $user->id,
            'goal' => '/',
            'state' => '1234',
        ]);
        Session::save();
    }
}
