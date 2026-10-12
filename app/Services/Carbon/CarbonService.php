<?php

namespace App\Services\Carbon;

use App\Models\EmissionEntry;
use App\Models\EmissionFactor;
use App\Models\User;

class CarbonService
{
    /**
     * Calculate carbon footprint estimate based on activity and category.
     *
     * @return array{factor_id: ?int, factor_value: float, unit: string, co2e_kg: float, source: string}
     */
    public function calculate(string $category, float $activityValue): array
    {
        $factor = EmissionFactor::where('category', $category)->latest('effective_date')->first();

        if (! $factor) {
            // Conservative fallback factor
            $co2eFactor = 0.50;
            $unit = 'unit';
            $source = 'Estimasi Standar Konservatif';
            $factorId = null;
        } else {
            $co2eFactor = (float) $factor->co2e_factor;
            $unit = $factor->unit;
            $source = $factor->source_reference.' ('.$factor->version.')';
            $factorId = $factor->id;
        }

        $calculatedCo2e = round($activityValue * $co2eFactor, 2);

        return [
            'factor_id' => $factorId,
            'factor_value' => $co2eFactor,
            'unit' => $unit,
            'co2e_kg' => $calculatedCo2e,
            'source' => $source,
        ];
    }

    /**
     * Get aggregate user emission summary.
     *
     * @return array{total_co2e_kg: float, entries_count: int, by_category: array<string, float>}
     */
    public function getUserSummary(int $userId): array
    {
        $entries = EmissionEntry::where('user_id', $userId)->get();

        $totalCo2e = (float) $entries->sum('calculated_co2e');
        $byCategory = [];

        foreach ($entries as $entry) {
            $byCategory[$entry->category] = ($byCategory[$entry->category] ?? 0.0) + (float) $entry->calculated_co2e;
        }

        return [
            'total_co2e_kg' => round($totalCo2e, 2),
            'entries_count' => $entries->count(),
            'by_category' => $byCategory,
        ];
    }
}
