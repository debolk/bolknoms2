<?php

// Registering for meals
Route::get('/', 'RegisterController@index');
Route::post('/aanmelden', 'RegisterController@aanmelden');

// Creating and editing meals
Route::get('/administratie/nieuwe_maaltijd', 'MealController@new_meal');
Route::post('/administratie/nieuwe_maaltijd_maken', 'MealController@create');
Route::get('/administratie/{id}', 'MealController@show');

// Administration dashboard
Route::get('/administratie', 'AdminDashboardController@index');
Route::get('/administratie/verwijder/{id}', 'AdminDashboardController@verwijder');
Route::post('/administratie/aanmelden', 'AdminDashboardController@aanmelden');
Route::post('/administratie/afmelden/{id}', 'AdminDashboardController@afmelden');

// Information pages
Route::get('/disclaimer', 'PageController@disclaimer');
Route::get('/privacy', 'PageController@privacy');
Route::get('/top-eters', 'TopController@index');

// OAuth callback
Route::get('/oauth', ''); // Does nothing, handled in filter
