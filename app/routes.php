<?php

Route::get('/inschrijven', 'Front@index');
Route::get('/inschrijven/{id}', ['as' => 'inschrijven_specifiek', 'uses' => 'Front@inschrijven_specifiek']);
Route::get('/aanmelden/{id}', ['as' => 'aanmelden_specifiek', 'uses' => 'Front@aanmelden_specifiek']);
Route::get('/uitgebreid-inschrijven', 'Front@uitgebreidinschrijven');
Route::get('/uitgebreidaanmelden', 'Front@uitgebreidaanmelden');
Route::get('/aanmelden', 'Front@aanmelden');
Route::get('/afmelden/{id}/{salt}', 'Front@afmelden');
Route::get('/disclaimer', 'Front@disclaimer');
Route::get('/privacy', 'Front@privacy');
Route::get('/', 'Front@index');
