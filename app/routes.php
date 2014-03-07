<?php

// Front-end routes
Route::get('/inschrijven', 'Front@index');
Route::get('/inschrijven/{id}', ['as' => 'inschrijven_specifiek', 'uses' => 'Front@inschrijven_specifiek']);
Route::post('/aanmelden/{id}', ['as' => 'aanmelden_specifiek', 'uses' => 'Front@aanmelden_specifiek']);
Route::get('/uitgebreid-inschrijven', 'Front@uitgebreidinschrijven');
Route::post('/uitgebreidaanmelden', 'Front@uitgebreidaanmelden');
Route::post('/aanmelden', 'Front@aanmelden');
Route::get('/disclaimer', 'Front@disclaimer');
Route::get('/privacy', 'Front@privacy');
Route::get('/', 'Front@index');

// Deregister from a meal
Route::get('/afmelden/{id}/{salt}', 'DeregisterController@afmelden');

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

// OAuth callback
Route::get('/oauth', ''); // Does nothing, handled in filter