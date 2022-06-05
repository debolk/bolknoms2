<?php

Route::middleware('web')->group(function () {

    // Register for meals
    Route::get('/', 'Register@index');
    Route::post('/aanmelden', 'Register@aanmelden');
    Route::post('/afmelden', 'Register@afmelden');

    // Confirm registration
    Route::get('/bevestigen/{id}/{salt}', 'Confirm@confirm');

    // Information pages
    Route::get('/spelregels', 'Page@spelregels');
    Route::get('/disclaimer', 'Page@disclaimer');
    Route::get('/privacy', 'Page@privacy');

    // OAuth routes
    Route::get('/oauth', 'OAuth@callback');
    Route::get('/login', 'OAuth@login');
    Route::get('/logout', 'OAuth@logout');

    // Profile picture of a user
    Route::get('/photo/{username}', 'ProfilePicture@photoFor')->name('photo.src');

    // Pages which require member-level authorisation
    Route::middleware('oauth')->group(function () {

        // Picture of the current user
        Route::get('/photo', 'ProfilePicture@photo');

        // Top eaters list
        Route::get('/top-eters', 'Top@index');

        // My Profile page
        Route::get('/profiel', 'Profile@index');
        Route::post('/handicap', 'Profile@setHandicap');
    });

    // Administration pages
    Route::prefix('/administratie/')->middleware('oauth', 'board')->namespace('Administration')->group(function () {

        // Administration dashboard
        Route::get('', 'Dashboard@index');

        // Managing meals
        Route::prefix('/maaltijden/')->group(function () {
            // List of meals
            Route::get('', 'Meals@index');
            Route::get('/verwijder/{id}', 'Meals@verwijder');

            // Show meals in the backend
            Route::get('{id}', 'ShowMeal@show');
            Route::post('afmelden/{id}', 'ShowMeal@afmelden');
            Route::post('aanmelden', 'ShowMeal@aanmelden');

            // Create new meals
            Route::get('nieuwe_maaltijd', 'CreateMeal@index');
            Route::post('nieuwe_maaltijd', 'CreateMeal@create');

            // Update existing meals
            Route::get('{id}/edit', 'UpdateMeal@edit');
            Route::post('{id}', 'UpdateMeal@update');
        });

        // Managing users
        Route::prefix('/gebruikers/')->group(function () {
            Route::get('', 'Users@index');
            Route::post('{id}/handicap', 'Users@setHandicap');
            Route::post('{id}/blokkeren', 'Users@block');
            Route::post('{id}/vrijgeven', 'Users@release');
        });

        // Vacation periods
        Route::resource('vakanties', 'Vacations', [
            'names' => 'vacations',]);
    });
});
