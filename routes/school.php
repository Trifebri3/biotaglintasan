<?php

use App\Http\Controllers\School\SchoolController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [SchoolController::class, 'index'])->name('dashboard');
Route::post('/verify-student/{id}', [SchoolController::class, 'verifyStudent'])->name('verify-student');
Route::post('/classes', [SchoolController::class, 'createClass'])->name('create-class');
