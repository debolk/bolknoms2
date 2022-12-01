<?php

use App\Http\Controllers\API\MealsController;

Route::middleware('auth:sanctum')
    ->name('api.')
    ->group(function () {

        Route::get('meals/upcoming', [MealsController::class, 'upcoming'])->name('meals.upcoming');
    });
