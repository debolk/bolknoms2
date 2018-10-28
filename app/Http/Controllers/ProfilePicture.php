<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ProfilePicture as Picture;
use App\Models\User;

class ProfilePicture extends Application
{
    /**
     * Serves a photo of the currently logged-in user
     * @param  \App\Http\Helpers\ProfilePicture $picture
     * @return \Illuminate\Http\Response
     */
    public function photo(Picture $picture)
    {
        $user = $this->oauth->user();
        return $this->serveProfilePicture($picture, $user);
    }

    /**
     * Serves a profile picture of a named user
     * @param  string $username
     * @param  \App\Http\Helpers\ProfilePicture $picture
     * @return \Illuminate\Http\Response
     */
    public function photoFor($username, Picture $picture)
    {
        $user = User::where('username', $username)->first();
        if (!$user) {
            abort(404);
        }
        return $this->serveProfilePicture($picture, $user)
                ->header('Cache-Control', 'public, max-age=604800'); // 1 week
    }

    /**
     * Common functionality to serve a profile picture for a specific user
     * @param  \App\Http\Helpers\ProfilePicture $picture
     * @param  \App\Models\User                 $user
     * @return \Illuminate\Http\Response
     */
    private function serveProfilePicture(Picture $picture, User $user)
    {
        $image = $picture->getPictureFor($user);
        $mime = $picture->mimetypeFor($user);

        return response($image)->header('Content-Type', $mime);
    }
}
