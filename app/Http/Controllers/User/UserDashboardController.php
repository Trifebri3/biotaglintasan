<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Adoption;
use App\Models\EmissionEntry;
use App\Models\GreenAction;
use App\Models\School;
use App\Models\SchoolMembership;
use App\Models\UserActionLog;
use App\Services\Adoption\AdoptionService;
use App\Services\Carbon\CarbonService;
use App\Services\QR\QRService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserDashboardController extends Controller
{
    public function __construct(
        protected AdoptionService $adoptionService,
        protected QRService $qrService,
        protected CarbonService $carbonService
    ) {}

    public function index(Request $request): Response
    {
        $user = $request->user();

        // 1. My adopted trees
        $adoptions = Adoption::with([
            'tree.location',
            'tree.activeQrCode',
            'tree.observations' => fn ($q) => $q->with('observer')->latest('observed_at')->limit(6),
        ])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->get();

        // 2. Green Actions
        $greenActions = GreenAction::where('is_active', true)->get();
        $completedTodayIds = UserActionLog::where('user_id', $user->id)
            ->whereDate('completed_at', today())
            ->pluck('green_action_id')
            ->toArray();

        // 3. Carbon footprint summary
        $carbonSummary = $this->carbonService->getUserSummary($user->id);
        $recentEmissions = EmissionEntry::where('user_id', $user->id)
            ->latest('period_start')
            ->limit(5)
            ->get();

        // 4. School membership
        $membership = SchoolMembership::with(['school', 'schoolClass'])
            ->where('user_id', $user->id)
            ->first();

        return Inertia::render('User/Dashboard', [
            'adoptions' => $adoptions,
            'greenActions' => $greenActions,
            'completedActionIds' => $completedTodayIds,
            'carbonSummary' => $carbonSummary,
            'recentEmissions' => $recentEmissions,
            'membership' => $membership,
        ]);
    }

    public function pending(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if ($user->status === 'active') {
            return redirect()->route('user.dashboard');
        }

        $membership = SchoolMembership::with('school')->where('user_id', $user->id)->first();
        $availableSchools = School::where('is_active', true)->get(['id', 'name', 'code', 'city']);

        return Inertia::render('User/PendingVerification', [
            'membership' => $membership,
            'availableSchools' => $availableSchools,
        ]);
    }

    public function submitSchoolCode(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_code' => ['required', 'string', 'exists:schools,code'],
        ]);

        $user = $request->user();
        $school = School::where('code', $validated['school_code'])->firstOrFail();

        SchoolMembership::updateOrCreate(
            ['user_id' => $user->id],
            [
                'school_id' => $school->id,
                'role' => 'student',
                'status' => 'pending',
            ]
        );

        return redirect()->back()->with('success', 'Kode sekolah '.$school->name.' berhasil dikaitkan. Guru Anda akan segera memverifikasi.');
    }

    public function previewQr(Request $request): Response|RedirectResponse
    {
        $token = $request->query('token');

        if (! $token) {
            return redirect()->route('user.dashboard')->with('error', 'Token QR tidak ditemukan.');
        }

        $resolved = $this->qrService->resolveToken($token);

        return Inertia::render('User/ScanPreview', [
            'token' => $token,
            'result' => $resolved,
        ]);
    }

    public function adoptTree(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();
        $result = $this->adoptionService->adoptViaToken($validated['token'], $user->id, $validated['notes'] ?? null);

        if (! $result['success']) {
            return redirect()->back()->with('error', $result['message']);
        }

        return redirect()->route('user.dashboard')->with('success', $result['message']);
    }

    public function logAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'green_action_id' => ['required', 'exists:green_actions,id'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        UserActionLog::create([
            'user_id' => $user->id,
            'green_action_id' => $validated['green_action_id'],
            'completed_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Aksi hijau berhasil dicatat! Terima kasih telah berkontribusi.');
    }

    public function recordEmission(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string'],
            'activity_value' => ['required', 'numeric', 'min:0.1'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'notes' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        $calculation = $this->carbonService->calculate($validated['category'], (float) $validated['activity_value']);

        EmissionEntry::create([
            'user_id' => $user->id,
            'emission_factor_id' => $calculation['factor_id'],
            'category' => match ($validated['category']) {
                'electricity' => 'Listrik Rumah Tangga',
                'transport_motorcycle' => 'Perjalanan Sepeda Motor',
                'transport_gasoline' => 'Perjalanan Mobil Bensin',
                'transport_public' => 'Angkutan Umum / Bus / MRT',
                default => 'Lainnya',
            },
            'activity_value' => $validated['activity_value'],
            'unit' => $calculation['unit'],
            'calculated_co2e' => $calculation['co2e_kg'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Estimasi jejak karbon berhasil dicatat ('.$calculation['co2e_kg'].' kg CO2e).');
    }
}
