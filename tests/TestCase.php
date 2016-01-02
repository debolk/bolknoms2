<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

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
}
