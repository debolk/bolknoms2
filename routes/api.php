<?php

use App\Http\Controllers\API\MealsController;
use App\Http\Controllers\API\RegistrationsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'version'])
    ->name('api.')
    ->group(function () {

        Route::get('meals/upcoming', [MealsController::class, 'upcoming'])->name('meals.upcoming');
        Route::post('meals/{meal}/registrations', [RegistrationsController::class, 'store'])->name('meals.registrations');
        Route::post('meals/{meal}/registrations/{registration}', [RegistrationsController::class, 'destroy'])->name('meals.registrations.destroy');
    });
