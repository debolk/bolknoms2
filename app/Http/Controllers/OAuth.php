<?php

namespace App\Http\Controllers;

use App\BolkLogin\BolkLoginService;
use App\Exceptions\BolkLoginUnknownException;
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
            $token = Socialite::driver('BolkLogin')->user();
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

        // Find or create user
        try {
            $details = app(BolkLoginService::class)->userDetails($token);
        } catch (BolkLoginUnknownException) {
            return redirect(route('register.index'))->with('action_result', [
                'status' => 'error',
                'message' => 'Je bent niet \'bekend\' in Bolklogin en kunt Bolknoms niet gebruiken. Neem contact op met het bestuur.',
            ]);
        }

        $user = User::updateOrCreate([
            'username' => $details['username'],
        ], [
            'email' => $details['email'],
            'name' => $details['name'],
            'is_board' => app(BolkLoginService::class)->isBoardMember($token),
        ]);
        app(ProfilePicture::class)->updatePictureFor($user, $token->token);

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
