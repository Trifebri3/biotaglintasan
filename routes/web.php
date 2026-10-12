<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\PublicController;
use App\Http\Middleware\EnsureUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Area (Lampiran A & Bagian 6A)
Route::get('/', [PublicController::class, 'welcome'])->name('home');
Route::get('/peta-pohon', [PublicController::class, 'map'])->name('public.map');
Route::get('/edukasi', [PublicController::class, 'education'])->name('public.education');
Route::get('/tentang', [PublicController::class, 'about'])->name('public.about');

// Smart Dashboard Redirect according to Role
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user->isBioTagSuperAdmin()) {
        return redirect()->route('biotag.dashboard');
    }

    if ($user->isLintasanAdmin()) {
        return redirect()->route('lintasan.dashboard');
    }

    if ($user->isSchoolAdmin()) {
        return redirect()->route('school.dashboard');
    }

    if ($user->isOfficer()) {
        return redirect()->route('officer.dashboard');
    }

    if ($user->status === 'pending_verification') {
        return redirect()->route('user.pending');
    }

    return redirect()->route('user.dashboard');
})->middleware(['auth'])->name('dashboard');

// User / Student Area (Jejak Hijau Keluargaku)
Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(base_path('routes/user.php'));

// Officer / Tree Inspector Area
Route::middleware(['auth', EnsureUserRole::class.':officer,biotag_super_admin'])
    ->prefix('officer')
    ->name('officer.')
    ->group(base_path('routes/officer.php'));

// School / Teacher Area
Route::middleware(['auth', EnsureUserRole::class.':school_admin,biotag_super_admin'])
    ->prefix('school')
    ->name('school.')
    ->group(base_path('routes/school.php'));

// Yayasan Lintasan Admin Area
Route::middleware(['auth', EnsureUserRole::class.':lintasan_admin,biotag_super_admin'])
    ->prefix('lintasan')
    ->name('lintasan.')
    ->group(base_path('routes/lintasan.php'));

// BioTag Super Admin Area
Route::middleware(['auth', EnsureUserRole::class.':biotag_super_admin'])
    ->prefix('biotag')
    ->name('biotag.')
    ->group(base_path('routes/biotag.php'));

// User Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
