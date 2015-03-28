<?php

// Registering for meals
Route::get('/', 'RegisterController@index');
Route::post('/aanmelden', 'RegisterController@aanmelden');

// Creating and editing meals
Route::get('/administratie/nieuwe_maaltijd', 'MealController@new_meal');
Route::post('/administratie/nieuwe_maaltijd_maken', 'MealController@create');
Route::get('/administratie/{id}', 'MealController@show');
Route::post('/administratie/afmelden/{id}', 'MealController@afmelden');
Route::post('/administratie/aanmelden', 'MealController@aanmelden');

// Administration dashboard
Route::get('/administratie', 'AdminDashboardController@index');
Route::get('/administratie/verwijder/{id}', 'AdminDashboardController@verwijder');

// Information pages
Route::get('/disclaimer', 'PageController@disclaimer');
Route::get('/privacy', 'PageController@privacy');
Route::get('/top-eters', 'TopController@index');

// OAuth callback
Route::get('/oauth', ''); // Does nothing, handled in filter
