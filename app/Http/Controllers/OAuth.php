<?php

namespace App\Http\Controllers;

use App;
use App\Http\Helpers\OAuth as OAuthHelper;
use App\Http\Helpers\ProfilePicture;
use Request;

class OAuth extends Application
{
    /**
     * Store the callback
     * @return View
     */
    public function callback(ProfilePicture $profilePicture)
    {
        $result = $this->oauth->processCallback(Request::all());

        // Update the profile picture for this user
        if ($this->oauth->user()) {
            $profilePicture->updatePictureFor($this->oauth->user());
        }

        return redirect($result);
    }

    public function login()
    {
        return $this->oauth->toAuthorisationServer('/');
    }

    /**
     * Logs out the current user
     * @return Redirect to previous page
     */
    public function logout()
    {
        $this->oauth->logout();
    }
}
