<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tree extends Model
{
    use HasFactory;

    protected $fillable = [
        'tree_code',
        'species_name',
        'common_name',
        'description',
        'photo_url',
        'planted_date',
        'status',
        'co2_absorption_kg_year',
    ];

    protected function casts(): array
    {
        return [
            'planted_date' => 'date',
            'co2_absorption_kg_year' => 'decimal:2',
        ];
    }

    public function location(): HasOne
    {
        return $this->hasOne(TreeLocation::class);
    }

    public function qrCodes(): HasMany
    {
        return $this->hasMany(QRCode::class);
    }

    public function activeQrCode(): HasOne
    {
        return $this->hasOne(QRCode::class)->where('status', 'active');
    }

    public function adoptions(): HasMany
    {
        return $this->hasMany(Adoption::class);
    }

    public function currentAdoption(): HasOne
    {
        return $this->hasOne(Adoption::class)->where('status', 'active');
    }

    public function observations(): HasMany
    {
        return $this->hasMany(TreeObservation::class);
    }
}
