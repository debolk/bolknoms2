<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ProfilePicture as Picture;
use App\Models\User;
use Illuminate\Http\Response;

class ProfilePicture extends Controller
{
    /**
     * Serves a photo of the currently logged-in user
     */
    public function photo(Picture $picture): Response
    {
        $user = $this->oauth->user();
        if ($user === null) {
            return response()->noContent(404);
        }

        return $this->serveProfilePicture($picture, $user);
    }

    /**
     * Serves a profile picture of a named user
     */
    public function photoFor(string $username, Picture $picture): Response
    {
        $user = User::where('username', $username)->first();
        if (! $user) {
            abort(404);
        }

        return $this->serveProfilePicture($picture, $user)
                ->header('Cache-Control', 'public, max-age=604800'); // 1 week
    }

    /**
     * Common functionality to serve a profile picture for a specific user
     */
    private function serveProfilePicture(Picture $picture, User $user): Response
    {
        $image = $picture->getPictureFor($user);
        $mime = $picture->mimetypeFor($user);

        if ($mime) {
            return response($image)->header('Content-Type', $mime);
        } else {
            return response($image);
        }
    }
}
