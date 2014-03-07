<?php

// Registering for meals
Route::get('/inschrijven', 'RegisterController@index');
Route::get('/inschrijven/{id}', ['as' => 'inschrijven_specifiek', 'uses' => 'RegisterController@inschrijven_specifiek']);
Route::post('/aanmelden/{id}', ['as' => 'aanmelden_specifiek', 'uses' => 'RegisterController@aanmelden_specifiek']);
Route::get('/uitgebreid-inschrijven', 'RegisterController@uitgebreidinschrijven');
Route::post('/uitgebreidaanmelden', 'RegisterController@uitgebreidaanmelden');
Route::post('/aanmelden', 'RegisterController@aanmelden');
Route::get('/', 'RegisterController@index');

// Deregister from a meal
Route::get('/afmelden/{id}/{salt}', ['as' => 'afmelden', 'uses' =>'DeregisterController@afmelden');

// Creating and editing meals
Route::get('/administratie/nieuwe_maaltijd', 'MealController@new_meal');
Route::post('/administratie/nieuwe_maaltijd_maken', 'MealController@create');
Route::get('/administratie/bewerk/{id}', 'MealController@edit');
Route::post('/administratie/update/{id}', ['as' => 'update_meal', 'uses' => 'MealController@update']);
Route::get('/administratie/gevulde_dagen', 'MealController@gevulde_dagen');

// Printing checklist
Route::get('/administratie/checklist/{id}', 'PrintController@checklist');

// Administration dashboard
Route::get('/administratie', 'DashboardController@index');
Route::get('/administratie/verwijder/{id}', 'DashboardController@verwijder');
Route::post('/administratie/aanmelden', 'DashboardController@aanmelden');
Route::post('/administratie/afmelden/{id}', 'DashboardController@afmelden');

// Information pages
Route::get('/disclaimer', 'PageController@disclaimer');
Route::get('/privacy', 'PageController@privacy');

// OAuth callback
Route::get('/oauth', ''); // Does nothing, handled in filter