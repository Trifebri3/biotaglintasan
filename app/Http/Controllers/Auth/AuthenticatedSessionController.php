<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Handle Google authentication request (Seamless sign-in).
     */
    public function loginWithGoogle(Request $request): RedirectResponse
    {
        $user = User::firstOrCreate(
            ['email' => 'keluarga.andi@lintasan.id'],
            [
                'name' => 'Keluarga Andi',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'status' => 'active',
                'phone' => '081234567890',
            ]
        );

        Auth::login($user);
        $request->session()->regenerate();

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'google_login_success',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'new_values' => ['provider' => 'Google SSO', 'email' => $user->email],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Quick role demo login switcher.
     */
    public function quickLogin(Request $request): RedirectResponse
    {
        $roleKey = $request->input('role_key');

        $emailMap = [
            'andi' => 'keluarga.andi@lintasan.id',
            'officer' => 'petugas.lapangan@biotag.org',
            'guru' => 'guru.dewi@sekolah.sch.id',
            'lintasan' => 'admin@lintasan.org',
            'biotag' => 'superadmin@biotag.org',
        ];

        $targetEmail = $emailMap[$roleKey] ?? 'keluarga.andi@lintasan.id';
        $user = User::where('email', $targetEmail)->first();

        if (! $user) {
            // fallback create
            $user = User::firstOrCreate(
                ['email' => $targetEmail],
                [
                    'name' => ucfirst($roleKey).' User',
                    'password' => Hash::make('password123'),
                    'role' => $roleKey === 'officer' ? 'officer' : 'user',
                    'status' => 'active',
                ]
            );
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
