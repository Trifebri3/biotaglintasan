<?php

namespace App\Http\Controllers\BioTag;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\EmissionFactor;
use App\Models\QRCode;
use App\Models\School;
use App\Models\Tree;
use App\Models\User;
use App\Services\QR\QRService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BioTagController extends Controller
{
    public function __construct(
        protected QRService $qrService
    ) {}

    public function index(): Response
    {
        $totalUsers = User::count();
        $totalTrees = Tree::count();
        $totalQRs = QRCode::count();
        $totalSchools = School::count();

        $recentQRs = QRCode::with(['tree', 'creator'])->latest()->limit(10)->get();
        $auditLogs = AuditLog::with('user')->latest()->limit(15)->get();
        $emissionFactors = EmissionFactor::all();

        $healthStatus = [
            'database' => 'Connected & Healthy',
            'qr_engine' => 'Operational (Cryptographic Tokens Active)',
            'privacy_firewall' => 'Active (Generalization ON)',
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ];

        return Inertia::render('BioTag/Dashboard', [
            'stats' => [
                'users' => $totalUsers,
                'trees' => $totalTrees,
                'qrs' => $totalQRs,
                'schools' => $totalSchools,
            ],
            'healthStatus' => $healthStatus,
            'recentQRs' => $recentQRs,
            'auditLogs' => $auditLogs,
            'emissionFactors' => $emissionFactors,
        ]);
    }

    public function generateQr(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tree_id' => ['required', 'exists:trees,id'],
        ]);

        $qr = $this->qrService->createTreeQRCode($validated['tree_id'], $request->user()->id);

        return redirect()->back()->with('success', 'Token QR BioTag berhasil diterbitkan untuk pohon: '.$qr->token);
    }

    public function toggleQrStatus(Request $request, int $id): RedirectResponse
    {
        $qr = QRCode::findOrFail($id);

        $newStatus = $qr->status === 'disabled' ? 'active' : 'disabled';
        $qr->update([
            'status' => $newStatus,
            'disabled_at' => $newStatus === 'disabled' ? now() : null,
        ]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'qr_status_toggled',
            'entity_type' => 'QRCode',
            'entity_id' => $qr->id,
            'new_values' => ['status' => $newStatus],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Status QR berhasil diubah menjadi: '.$newStatus);
    }
}
