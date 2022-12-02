<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function reset()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        $token = $user->createToken('default');
        return ['token' => $token->plainTextToken];
    }
}
