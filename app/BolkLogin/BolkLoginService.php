<?php

namespace App\BolkLogin;

use App\Exceptions\BolkLoginUnknownException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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

        try {
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
        } catch (ClientException $e) {
            // Check for access denied errors
            $status = $e->getResponse()->getStatusCode();
            if ($status === 403) {
                $error = json_decode((string) $e->getResponse()->getBody(), false, 512, JSON_THROW_ON_ERROR);
                if ($error->error === 'unauthorized') {
                    throw new BolkLoginUnknownException();
                }
            }

            throw $e;
        }
    }

    public function isBoardMember(User $user): bool
    {
        try {
            $client = app(Client::class);
            $url = 'https://auth.debolk.nl/bestuur/?access_token=' . $user->token;
            $client->get($url);
            return true;
        } catch (ClientException $e) {
            // Non-board members will return a 403 with JSON error: unauthorized
            $body = json_decode((string) $e->getResponse()->getBody());
            if (isset($body->error) && $body->error === 'unauthorized') {
                return false;
            } else {
                throw $e;
            }
        } catch (Throwable $e) {
            app('sentry')->captureException($e);
            return false;
        }
    }
}
