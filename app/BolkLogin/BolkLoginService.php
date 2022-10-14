<?php

namespace App\BolkLogin;

use GuzzleHttp\Client;
use Laravel\Socialite\Two\User;
use Throwable;

/**
 * Abstracts the secondary functions of the auth.debolk.nl services that are not in the
 * OAuth standard like getting user details and authorisation levels.
 */
class BolkLoginService
{
    public function userDetails(User $user): array
    {
        $client = app(Client::class);

        $username = null;
        $url = 'https://auth.debolk.nl/resource/?access_token=' . $user->token;
        $response = $client->get($url);
        $username = json_decode($response->getBody())->user_id;

        $url = 'https://people.debolk.nl/persons/' . $username . '/basic?access_token=' . $user->token;
        $response = $client->get($url);
        $basicData = json_decode($response->getBody());

        return [
            'username' => $username,
            'name' => $basicData->name,
            'email' => $basicData->email,
        ];
    }

    public function isBoardMember(User $user): bool
    {
        try {
            $client = app(Client::class);
            $url = 'https://auth.debolk.nl/bestuur/?access_token=' . $user->token;
            $request = $client->get($url);

            return $request->getStatusCode() === 200;
        } catch (Throwable $e) {
            app('sentry')->captureException($e);
            return false;
        }
    }
}
