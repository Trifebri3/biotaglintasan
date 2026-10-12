<?php

use App\Http\Controllers\BioTag\BioTagController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [BioTagController::class, 'index'])->name('dashboard');
Route::post('/generate-qr', [BioTagController::class, 'generateQr'])->name('generate-qr');
Route::post('/toggle-qr/{id}', [BioTagController::class, 'toggleQrStatus'])->name('toggle-qr');
