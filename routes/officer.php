<?php

use App\Http\Controllers\Officer\OfficerController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [OfficerController::class, 'index'])->name('dashboard');
Route::post('/observations', [OfficerController::class, 'storeObservation'])->name('store-observation');
