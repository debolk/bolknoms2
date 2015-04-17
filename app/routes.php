<?php

// Registering for meals
Route::get('/', 'RegisterController@index');
Route::post('/aanmelden', 'RegisterController@aanmelden');

// Create new meals
Route::get('/administratie/nieuwe_maaltijd', 'CreateMealController@new_meal');
Route::post('/administratie/nieuwe_maaltijd_maken', 'CreateMealController@create');

// Show meals in the backend
Route::get('/administratie/{id}', 'ShowMealController@show');
Route::post('/administratie/afmelden/{id}', 'ShowMealController@afmelden');
Route::post('/administratie/aanmelden', 'ShowMealController@aanmelden');

// Update meals
Route::get('/administratie/{id}/edit', 'UpdateMealController@edit');
Route::post('/administratie/{id}', 'UpdateMealController@update');

// Administration dashboard
Route::get('/administratie', 'AdminDashboardController@index');
Route::get('/administratie/verwijder/{id}', 'AdminDashboardController@verwijder');

// Information pages
Route::get('/disclaimer', 'PageController@disclaimer');
Route::get('/privacy', 'PageController@privacy');
Route::get('/top-eters', 'TopController@index');

// OAuth callback
Route::get('/oauth', ''); // Does nothing, handled in filter
