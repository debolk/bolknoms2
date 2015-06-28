<?php

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
Route::get('/voordeel-account', 'Page@voordeelaccount');

// OAuth routes
Route::get('/oauth', 'OAuth@callback');
Route::get('/login', 'OAuth@login');
Route::get('/logout', 'OAuth@logout');

// Pages which require member-level authorisation
Route::group(['middleware' => ['oauth']], function(){

    // Personal details of the user
    Route::get('/photo', 'OAuth@photo');

    // Top eaters list
    Route::get('/top-eters', 'Top@index');

    // My Profile page
    Route::get('/profiel', 'Profile@index');
    Route::post('/handicap', 'Profile@setHandicap');
});

// Pages which require board-level authorization
Route::group(['middleware' => ['oauth','board']], function(){

    // Administration dashboard
    Route::get('/administratie', 'AdminDashboard@index');
    Route::get('/administratie/verwijder/{id}', 'AdminDashboard@verwijder');

    // Show meals in the backend
    Route::get('/administratie/{id}', 'ShowMeal@show');
    Route::post('/administratie/afmelden/{id}', 'ShowMeal@afmelden');
    Route::post('/administratie/aanmelden', 'ShowMeal@aanmelden');

    // Create new meals
    Route::get('/administratie/nieuwe_maaltijd', 'CreateMeal@new_meal');
    Route::post('/administratie/nieuwe_maaltijd_maken', 'CreateMeal@create');

    // Update existing meals
    Route::get('/administratie/{id}/edit', 'UpdateMeal@edit');
    Route::post('/administratie/{id}', 'UpdateMeal@update');
});
