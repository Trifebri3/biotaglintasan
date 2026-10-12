<?php

namespace App\Services\QR;

use App\Models\AuditLog;
use App\Models\QRCode;
use App\Models\Tree;
use Illuminate\Support\Str;

class QRService
{
    /**
     * Generate a cryptographically secure random token for QR Code.
     */
    public function generateToken(): string
    {
        return 'bio_qr_'.Str::random(32);
    }

    /**
     * Create and attach a new QR code to a tree.
     */
    public function createTreeQRCode(int $treeId, ?int $createdBy = null): QRCode
    {
        $token = $this->generateToken();

        $qr = QRCode::create([
            'tree_id' => $treeId,
            'token' => $token,
            'status' => 'active',
            'created_by' => $createdBy,
        ]);

        AuditLog::create([
            'user_id' => $createdBy,
            'action' => 'qr_code_created',
            'entity_type' => 'QRCode',
            'entity_id' => $qr->id,
            'new_values' => ['tree_id' => $treeId, 'token' => $token],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return $qr;
    }

    /**
     * Validate and resolve QR token to find the tree.
     *
     * @return array{success: bool, message: string, tree: ?Tree, qr: ?QRCode}
     */
    public function resolveToken(string $token): array
    {
        $qr = QRCode::with(['tree.location', 'tree.currentAdoption.user'])->where('token', $token)->first();

        if (! $qr) {
            return [
                'success' => false,
                'message' => 'Kode QR tidak dikenali atau tidak valid dalam sistem BioTag.',
                'tree' => null,
                'qr' => null,
            ];
        }

        if ($qr->status === 'disabled') {
            return [
                'success' => false,
                'message' => 'Kode QR ini telah dinonaktifkan oleh administrator.',
                'tree' => $qr->tree,
                'qr' => $qr,
            ];
        }

        if ($qr->status === 'used' || $qr->tree->status === 'adopted') {
            return [
                'success' => false,
                'message' => 'Pohon ini sudah memiliki keluarga adopsi aktif.',
                'tree' => $qr->tree,
                'qr' => $qr,
            ];
        }

        return [
            'success' => true,
            'message' => 'Pohon siap diadopsi.',
            'tree' => $qr->tree,
            'qr' => $qr,
        ];
    }
}
