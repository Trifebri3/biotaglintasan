<?php

namespace App\Http\Controllers\Lintasan;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\School;
use App\Models\Tree;
use App\Models\TreeLocation;
use App\Services\QR\QRService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LintasanController extends Controller
{
    public function __construct(
        protected QRService $qrService
    ) {}

    public function index(): Response
    {
        $schools = School::withCount(['memberships', 'classes'])->get();
        $trees = Tree::with(['location', 'activeQrCode', 'currentAdoption.user'])->latest()->get();

        $stats = [
            'total_schools' => $schools->count(),
            'total_trees' => $trees->count(),
            'adopted_trees' => $trees->where('status', 'adopted')->count(),
            'available_trees' => $trees->where('status', '!=', 'adopted')->count(),
        ];

        return Inertia::render('Lintasan/Dashboard', [
            'schools' => $schools,
            'trees' => $trees,
            'stats' => $stats,
        ]);
    }

    public function storeTree(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tree_code' => ['required', 'string', 'unique:trees,tree_code'],
            'species_name' => ['required', 'string'],
            'common_name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'planted_date' => ['nullable', 'date'],
            'co2_absorption_kg_year' => ['required', 'numeric', 'min:0'],
            'address_description' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $tree = Tree::create([
            'tree_code' => $validated['tree_code'],
            'species_name' => $validated['species_name'],
            'common_name' => $validated['common_name'],
            'description' => $validated['description'] ?? null,
            'planted_date' => $validated['planted_date'] ?? null,
            'status' => 'verified',
            'co2_absorption_kg_year' => $validated['co2_absorption_kg_year'],
        ]);

        // Generalize coordinate for public privacy
        $lat = $validated['latitude'] ?? -6.195000;
        $lng = $validated['longitude'] ?? 106.832000;

        TreeLocation::create([
            'tree_id' => $tree->id,
            'latitude' => $lat,
            'longitude' => $lng,
            'public_latitude' => round($lat, 3), // Generalized to 3 decimals (~100m)
            'public_longitude' => round($lng, 3),
            'address_description' => $validated['address_description'] ?? 'Taman Program Lintasan',
            'visibility' => 'generalized',
        ]);

        // Automatically generate secure QR code via QRService
        $this->qrService->createTreeQRCode($tree->id, $request->user()->id);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'tree_created',
            'entity_type' => 'Tree',
            'entity_id' => $tree->id,
            'new_values' => ['code' => $tree->tree_code, 'name' => $tree->common_name],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Pohon baru '.$tree->common_name.' ('.$tree->tree_code.') berhasil didaftarkan dan QR Code telah diterbitkan.');
    }
}
