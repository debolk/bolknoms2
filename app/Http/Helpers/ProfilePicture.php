<?php

namespace App\Http\Helpers;

use App\Http\Helpers\OAuth;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProfilePicture
{
    /**
     * Updates the local cached profile picture of a user
     * @param  User   $user
     * @return void
     */
    public static function updatePictureFor(User $user)
    {
        try {
            $client = new Client;
            $token = OAuth::getAccessToken();
            $url = 'https://people.debolk.nl/persons/'.$user->username.'/photo/256/256?access_token='.$token;
            $response = $client->get($url, ['sink' => self::picturePathFor($user)]);
        }
        catch (Exception $e) {
            // No handling needed, we'll just not have an image available
        }
    }

    /**
     * Return a picture for a specific user
     * @param  User   $user
     * @return string
     */
    public static function getPictureFor(User $user)
    {
        if (! File::exists(self::picturePathFor($user))) {
            self::updatePictureFor($user);
        }

        return File::get(self::picturePathFor($user));
    }

    /**
     * Determine the path to the a specific profile picture
     * @param  User   $user user to get a picture for
     * @return string       full filesystem path to picture
     */
    private static function picturePathFor(User $user)
    {
        return base_path() . '/uploads/profile_pictures/' . $user->id . '.jpg';
    }
}
