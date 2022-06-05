<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Helpers\OAuth as OAuthHelper;
use App\Http\Helpers\ProfilePicture;
use Illuminate\Http\RedirectResponse;

class OAuth extends Controller
{
    /**
     * Store the callback
     */
    public function callback(Request $request, ProfilePicture $profilePicture): RedirectResponse
    {
        $result = $this->oauth->processCallback($request->all());

        // Update the profile picture for this user
        $user = $this->oauth->user();
        if ($user) {
            $profilePicture->updatePictureFor($user);
        }

        return redirect()->to($result);
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
