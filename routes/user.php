<?php

use App\Http\Controllers\User\UserDashboardController;
use App\Http\Middleware\EnsureUserActive;
use Illuminate\Support\Facades\Route;

Route::get('/pending', [UserDashboardController::class, 'pending'])->name('pending');
Route::post('/submit-school-code', [UserDashboardController::class, 'submitSchoolCode'])->name('submit-school-code');

Route::middleware([EnsureUserActive::class])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/scan-preview', [UserDashboardController::class, 'previewQr'])->name('scan-preview');
    Route::post('/adopt-tree', [UserDashboardController::class, 'adoptTree'])->name('adopt-tree');
    Route::post('/log-action', [UserDashboardController::class, 'logAction'])->name('log-action');
    Route::post('/record-emission', [UserDashboardController::class, 'recordEmission'])->name('record-emission');
});
