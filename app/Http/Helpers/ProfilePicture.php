<?php

namespace App\Http\Helpers;

use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProfilePicture
{
    /**
     * Updates the local cached profile picture of a user
     */
    public function updatePictureFor(User $user, string $token): void
    {
        // Calculate location of image
        $path = $this->picturePathFor($user);

        try {
            $client = app(Client::class);
            $url = 'https://people.debolk.nl/person/' . $user->username . '/photo?access_token=' . $token;
            $file = fopen($path, 'w');
            $client->get($url, ['sink' => $file]);
        } catch (TransferException $exception) {
            Log::error('Failed to retrieve profile picture', ['exception' => $exception, 'user' => $user]);
            \Sentry\captureException($exception);
            @unlink($path);
            return;
        }

        if (!is_readable($path)) {
            @unlink($path);
            return;
        }

        // Check the mimetype of the resulting image
        // to make sure we have a valid image
        $mimeType = mime_content_type($path);
        if (!$mimeType || substr($mimeType, 0, 6) !== 'image/') {
            @unlink($path);
        }
    }

    /**
     * Return a picture for a specific user
     * @param  User   $user
     * @return string
     */
    public function getPictureFor(User $user)
    {
        $path = $this->picturePathFor($user);

        if (File::exists($path)) {
            return File::get($path);
        } else {
            return File::get(public_path() . '/images/swedishchef.jpg');
        }
    }

    /**
     * Correct mime-type to use for serving a profile picture of a user
     */
    public function mimetypeFor(User $user): ?string
    {
        $path = $this->picturePathFor($user);

        if (! File::exists($path)) {
            $path = public_path('images/swedishchef.jpg');
        }

        return mime_content_type($path) ?: null;
    }

    /**
     * Determine the path to the a specific profile picture
     * @param  User   $user user to get a picture for
     * @return string       full filesystem path to picture
     */
    private function picturePathFor(User $user)
    {
        return storage_path('app/public/profile_pictures/' . $user->id);
    }
}
