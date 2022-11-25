<?php

use App\Http\Controllers\Administration;
use App\Http\Controllers\Confirm;
use App\Http\Controllers\OAuth;
use App\Http\Controllers\Page;
use App\Http\Controllers\Profile;
use App\Http\Controllers\ProfilePicture;
use App\Http\Controllers\Register;
use App\Http\Controllers\Top;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    // Register for meals
    Route::get('/', [Register::class, 'index'])->name('register.index');
    Route::post('/aanmelden', [Register::class, 'aanmelden']);
    Route::post('/afmelden', [Register::class, 'afmelden']);

    // Confirm registration
    Route::get('/bevestigen/{id}/{salt}', [Confirm::class, 'confirm']);

    // Information pages
    Route::get('/spelregels', [Page::class, 'spelregels']);
    Route::get('/disclaimer', [Page::class, 'disclaimer']);
    Route::get('/privacy', [Page::class, 'privacy']);

    // OAuth routes
    Route::get('/oauth', [OAuth::class, 'callback']);
    Route::get('/login', [OAuth::class, 'login'])->name('login');
    Route::get('/logout', [OAuth::class, 'logout']);

    // Profile picture of a user
    Route::get('/photo/{username}', [ProfilePicture::class, 'photoFor'])->name('photo.src');

    // Pages which require member-level authorisation
    Route::middleware('auth')->group(function () {
        // Picture of the current user
        Route::get('/photo', [ProfilePicture::class, 'photo']);

        // Top eaters list
        Route::get('/top-eters', [Top::class, 'index']);

        // My Profile page
        Route::get('/profiel', [Profile::class, 'index']);
        Route::post('/handicap', [Profile::class, 'setHandicap']);
    });

    // Administration pages
    Route::prefix('/administratie/')->middleware('auth', 'board')->group(function () {
        // Administration dashboard
        Route::get('', [Administration\Dashboard::class, 'index']);

        // Managing meals
        Route::prefix('/maaltijden/')->group(function () {
            // List of meals
            Route::get('', [Administration\Meals::class, 'index']);
            Route::get('/verwijder/{id}', [Administration\Meals::class, 'verwijder']);

            // Show meals in the backend
            Route::get('{id}', [Administration\ShowMeal::class, 'show']);
            Route::post('afmelden/{id}', [Administration\ShowMeal::class, 'afmelden']);
            Route::post('aanmelden', [Administration\ShowMeal::class, 'aanmelden']);

            // Create new meals
            Route::get('nieuwe_maaltijd', [Administration\CreateMeal::class, 'index']);
            Route::post('nieuwe_maaltijd', [Administration\CreateMeal::class, 'create']);

            // Update existing meals
            Route::get('{id}/edit', [Administration\UpdateMeal::class, 'edit']);
            Route::post('{id}', [Administration\UpdateMeal::class, 'update']);
        });

        // Managing users
        Route::prefix('/gebruikers/')->group(function () {
            Route::get('', [Administration\Users::class, 'index']);
            Route::post('{id}/handicap', [Administration\Users::class, 'setHandicap']);
            Route::post('{id}/blokkeren', [Administration\Users::class, 'block']);
            Route::post('{id}/vrijgeven', [Administration\Users::class, 'release']);
        });

        // Vacation periods
        Route::resource('vakanties', Administration\Vacations::class, [
            'names' => 'vacations', ]);
    });
});
