<?php

namespace App\Services\Adoption;

use App\Models\Adoption;
use App\Models\AuditLog;
use App\Models\Tree;
use App\Services\QR\QRService;
use Illuminate\Support\Facades\DB;

class AdoptionService
{
    public function __construct(
        protected QRService $qrService
    ) {}

    /**
     * Adopt a tree using scanned QR token.
     *
     * @return array{success: bool, message: string, adoption: ?Adoption}
     */
    public function adoptViaToken(string $token, int $userId, ?string $notes = null): array
    {
        $resolved = $this->qrService->resolveToken($token);

        if (! $resolved['success'] || ! $resolved['tree'] || ! $resolved['qr']) {
            return [
                'success' => false,
                'message' => $resolved['message'],
                'adoption' => null,
            ];
        }

        $tree = $resolved['tree'];
        $qr = $resolved['qr'];

        return DB::transaction(function () use ($tree, $qr, $userId, $notes) {
            // Lock and verify
            $freshTree = Tree::lockForUpdate()->find($tree->id);
            if ($freshTree->status === 'adopted') {
                return [
                    'success' => false,
                    'message' => 'Pohon ini telah diadopsi oleh pengguna lain sesaat yang lalu.',
                    'adoption' => null,
                ];
            }

            // Create adoption record
            $adoption = Adoption::create([
                'tree_id' => $freshTree->id,
                'user_id' => $userId,
                'adopted_at' => now(),
                'status' => 'active',
                'notes' => $notes ?: 'Diadopsi via pemindaian kode QR BioTag resmi.',
            ]);

            // Update Tree & QR status
            $freshTree->update(['status' => 'adopted']);
            $qr->update(['status' => 'used']);

            AuditLog::create([
                'user_id' => $userId,
                'action' => 'tree_adopted',
                'entity_type' => 'Adoption',
                'entity_id' => $adoption->id,
                'new_values' => [
                    'tree_id' => $freshTree->id,
                    'tree_code' => $freshTree->tree_code,
                    'qr_token' => $qr->token,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return [
                'success' => true,
                'message' => 'Selamat! Pohon '.$freshTree->common_name.' ('.$freshTree->tree_code.') berhasil diadopsi.',
                'adoption' => $adoption,
            ];
        });
    }
}
