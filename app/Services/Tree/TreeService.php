<?php

namespace App\Services\Tree;

use App\Models\AuditLog;
use App\Models\Tree;
use App\Models\TreeObservation;
use Illuminate\Database\Eloquent\Collection;

class TreeService
{
    /**
     * Get trees for the public map (coordinates generalized to preserve privacy).
     *
     * @return Collection<int, Tree>
     */
    public function getPublicTrees(): Collection
    {
        return Tree::with(['location', 'currentAdoption.user'])
            ->whereIn('status', ['verified', 'adopted', 'monitored'])
            ->get();
    }

    /**
     * Record a new health/growth observation for a tree.
     */
    public function recordObservation(int $treeId, int $userId, string $conditionStatus, ?string $notes = null, ?string $photoUrl = null): TreeObservation
    {
        $observation = TreeObservation::create([
            'tree_id' => $treeId,
            'observed_by' => $userId,
            'condition_status' => $conditionStatus,
            'notes' => $notes,
            'photo_url' => $photoUrl,
            'observed_at' => now(),
        ]);

        // Update tree status to monitored
        $tree = Tree::find($treeId);
        if ($tree && $tree->status !== 'adopted') {
            $tree->update(['status' => 'monitored']);
        }

        AuditLog::create([
            'user_id' => $userId,
            'action' => 'tree_observed',
            'entity_type' => 'TreeObservation',
            'entity_id' => $observation->id,
            'new_values' => ['condition' => $conditionStatus, 'tree_id' => $treeId],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $observation;
    }
}
