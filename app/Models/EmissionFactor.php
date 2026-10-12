<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmissionFactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'unit',
        'co2e_factor',
        'version',
        'source_reference',
        'effective_date',
    ];

    protected function casts(): array
    {
        return [
            'co2e_factor' => 'decimal:4',
            'effective_date' => 'date',
        ];
    }

    public function entries(): HasMany
    {
        return $this->hasMany(EmissionEntry::class);
    }
}
