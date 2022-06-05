<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use App\Http\Helpers\OAuth as OAuthHelper;
use App\Http\Helpers\ProfilePicture;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;

class OAuth extends Controller
{
    /**
     * Store the callback
     */
    public function callback(ProfilePicture $profilePicture): RedirectResponse
    {
        $result = $this->oauth->processCallback(Request::all());

        // Update the profile picture for this user
        $user = $this->oauth->user();
        if ($user) {
            $profilePicture->updatePictureFor($user);
        }

        return redirect($result);
    }

    public function login(): RedirectResponse
    {
        return $this->oauth->toAuthorisationServer('/');
    }

    /**
     * Logs out the current user
     */
    public function logout(): void
    {
        $this->oauth->logout();
    }
}
