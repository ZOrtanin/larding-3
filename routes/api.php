<?php

use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\StatusController;
use App\Http\Controllers\Api\StatisticsController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [HealthController::class, 'show'])->name('api.health');


Route::middleware('api.token')->group(function () {
    Route::get('/statistics', [StatisticsController::class, 'show'])->name('api.statistics');
    Route::get('/status', [StatusController::class, 'show'])->name('api.status');
    Route::post('/leads', [LeadController::class, 'store'])
    ->middleware('throttle:lead-form')
    ->name('api.leads.store');
});
