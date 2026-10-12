<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Tree;
use App\Models\TreeObservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OfficerController extends Controller
{
    public function index(Request $request): Response
    {
        $trees = Tree::with(['location', 'activeQrCode', 'currentAdoption.user'])
            ->latest()
            ->get();

        $recentObservations = TreeObservation::with(['tree', 'observer'])
            ->latest('observed_at')
            ->limit(15)
            ->get();

        $stats = [
            'total_trees' => $trees->count(),
            'monitored_trees' => $trees->where('status', 'monitored')->count(),
            'adopted_trees' => $trees->where('status', 'adopted')->count(),
            'total_inspections' => TreeObservation::count(),
        ];

        return Inertia::render('Officer/Dashboard', [
            'trees' => $trees,
            'recentObservations' => $recentObservations,
            'stats' => $stats,
        ]);
    }

    public function storeObservation(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tree_id' => ['required', 'exists:trees,id'],
            'condition_status' => ['required', 'in:thriving,healthy,needs_care,withered'],
            'notes' => ['required', 'string', 'max:500'],
            'photo' => ['nullable', 'image', 'max:5120'], // 5MB max
            'photo_url' => ['nullable', 'string'],
        ]);

        $photoUrl = $validated['photo_url'] ?? '/images/tree-sample-1.jpg';

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = 'obs_'.time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/observations'), $fileName);
            $photoUrl = '/uploads/observations/'.$fileName;
        }

        $observation = TreeObservation::create([
            'tree_id' => $validated['tree_id'],
            'observed_by' => $request->user()->id,
            'condition_status' => $validated['condition_status'],
            'notes' => $validated['notes'],
            'photo_url' => $photoUrl,
            'observed_at' => now(),
        ]);

        $tree = Tree::find($validated['tree_id']);
        if ($tree && $tree->status !== 'adopted') {
            $tree->update(['status' => 'monitored']);
        }

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'officer_tree_observation_recorded',
            'entity_type' => 'TreeObservation',
            'entity_id' => $observation->id,
            'new_values' => [
                'tree_id' => $tree->id,
                'tree_code' => $tree->tree_code,
                'condition' => $validated['condition_status'],
                'has_photo' => ! empty($photoUrl),
            ],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Laporan dan foto kondisi pohon '.$tree->common_name.' ('.$tree->tree_code.') berhasil dikirim. Anak/keluarga dapat langsung melihat update ini.');
    }
}
