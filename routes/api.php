<?php

use App\Http\Controllers\API\MealsController;
use App\Http\Controllers\API\RegistrationsController;
use App\Http\Controllers\API\StartController;
use App\Http\Middleware\RequiresVersionHeader;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', RequiresVersionHeader::class])
    ->name('api.')
    ->group(function () {

        Route::get('/', StartController::class)->name('start');

        Route::get('meals/upcoming', [MealsController::class, 'upcoming'])->name('meals.upcoming');
        Route::post('meals/{meal}/registrations', [RegistrationsController::class, 'store'])->name('meals.registrations');
        Route::delete('meals/{meal}/registrations/{registration}', [RegistrationsController::class, 'destroy'])->name('meals.registrations.destroy');
    });
