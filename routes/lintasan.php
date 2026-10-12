<?php

use App\Http\Controllers\Lintasan\LintasanController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [LintasanController::class, 'index'])->name('dashboard');
Route::post('/trees', [LintasanController::class, 'storeTree'])->name('store-tree');
