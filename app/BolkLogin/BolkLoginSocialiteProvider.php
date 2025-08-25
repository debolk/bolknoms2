<?php

namespace App\BolkLogin;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class BolkLoginSocialiteProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = [];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://auth.debolk.nl/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://auth.debolk.nl/token';
    }

    protected function getUserByToken($token)
    {
        return [];
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user);
    }

    public static function getAuthorizations(User $user): array
    {
        return [];
    }
}
