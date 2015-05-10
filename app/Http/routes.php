<?php

// Register for meals
Route::get('/aanmelden', 'RegisterController@index');
Route::post('/aanmelden', 'RegisterController@aanmelden');

// Information pages
Route::get('/disclaimer', 'PageController@disclaimer');
Route::get('/privacy', 'PageController@privacy');

// OAuth authorization callback
Route::get('/oauth', 'OAuthController@callback');

Route::get('/login', 'OAuthController@login');
Route::get('/logout', 'OAuthController@logout');

// Pages which require a Bolk-account
Route::group(['middleware' => 'oauth'], function(){

    // Top eaters list
    Route::get('/top-eters', 'TopController@index');
});

Route::get('/currentuser', 'OAuthController@currentUser');

// Pages which require board-level authorization
Route::group(['middleware' => ['oauth','board']], function(){

    // Administration dashboard
    Route::get('/administratie', 'AdminDashboardController@index');
    Route::get('/administratie/verwijder/{id}', 'AdminDashboardController@verwijder');

    // Show meals in the backend
    Route::get('/administratie/{id}', 'ShowMealController@show');
    Route::post('/administratie/afmelden/{id}', 'ShowMealController@afmelden');
    Route::post('/administratie/aanmelden', 'ShowMealController@aanmelden');

    // Create new meals
    Route::get('/administratie/nieuwe_maaltijd', 'CreateMealController@new_meal');
    Route::post('/administratie/nieuwe_maaltijd_maken', 'CreateMealController@create');

    // Update existing meals
    Route::get('/administratie/{id}/edit', 'UpdateMealController@edit');
    Route::post('/administratie/{id}', 'UpdateMealController@update');
});
