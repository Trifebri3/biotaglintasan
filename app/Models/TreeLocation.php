<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tree_id',
        'latitude',
        'longitude',
        'public_latitude',
        'public_longitude',
        'address_description',
        'visibility',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'public_latitude' => 'float',
            'public_longitude' => 'float',
        ];
    }

    public function tree(): BelongsTo
    {
        return $this->belongsTo(Tree::class);
    }
}
