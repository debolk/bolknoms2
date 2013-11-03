<?php

Route::get('/inschrijven', 'Front@index');
Route::get('/inschrijven/{id}', 'Front@inschrijven_specifiek');
Route::get('/aanmelden/{id}', 'Front@aanmelden_specifiek');
Route::get('/uitgebreid-inschrijven', 'Front@uitgebreidinschrijven');
Route::get('/uitgebreidaanmelden', 'Front@uitgebreidaanmelden');
Route::get('/aanmelden', 'Front@aanmelden');
Route::get('/afmelden/{id}/{salt}', 'Front@afmelden');
Route::get('/disclaimer', 'Front@disclaimer');
Route::get('/privacy', '/privacy');
Route::get('/', 'Front@index');
