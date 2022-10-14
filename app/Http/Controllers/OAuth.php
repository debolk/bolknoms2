<?php

namespace App\Http\Controllers;

use App\BolkLogin\BolkLoginService;
use App\Http\Helpers\ProfilePicture;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class OAuth extends Controller
{
    public function callback(): RedirectResponse
    {
        try {
            $oauthUser = Socialite::driver('BolkLogin')->user();
        } catch (InvalidStateException) {
            // usually happens when someone goes back or refreshes in the process
            // causing our authorization token be invalid (single-use tokens)
            return redirect(route('register.index'))->with('action_result', [
                'status' => 'error',
                'message' => 'Inloggen niet gelukt (OAuth state mismatch). Probeer opnieuw.',
            ]);
        } catch (ClientException) {
            // refusing permission in bolklogin is not detected by Socialite
            // it just tries for an access token, which fails, so we catch it here
            return redirect(route('register.index'))->with('action_result', [
                'status' => 'error',
                'message' => 'Je hebt toestemming geweigerd om je gebruikersgegevens op te halen',
            ]);
        }

        $user = User::where('email', $oauthUser->getId())->first();

        // First time, create a new user
        $details = app(BolkLoginService::class)->userDetails($oauthUser);
        if (!$user) {
            $user = User::create($details);
        } else {
            $user->update($details);
        }

        // Set authorisations and update the profile picture
        $user->update(['is_board' => app(BolkLoginService::class)->isBoardMember($oauthUser)]);
        app(ProfilePicture::class)->updatePictureFor($user, $oauthUser->token);

        Auth::login($user);

        return redirect(route('register.index'));
    }

    public function login(): RedirectResponse
    {
        return Socialite::driver('BolkLogin')->redirect();
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect(route('register.index'));
    }
}
