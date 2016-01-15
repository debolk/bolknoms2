<?php

namespace App\Http\Controllers;

use App;
use App\Http\Helpers\OAuth;
use App\Http\Helpers\ProfilePicture as Picture;
use App\Models\User;

class ProfilePicture extends Application
{
    /**
     * Redirects to a photo of the user
     */
    public function photo()
    {
        return response(Picture::getPictureFor(OAuth::user()))->header('Content-Type', 'image/jpeg');
    }

    /**
     * Redirects to a photo of the user
     * @param  string $username
     */
    public function photoFor($username)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            abort(404);
        }

        return response(Picture::getPictureFor($user))
                ->header('Content-Type', 'image/jpeg')
                ->header('Cache-Control', 'public, max-age=604800'); // cache one week
    }
}
