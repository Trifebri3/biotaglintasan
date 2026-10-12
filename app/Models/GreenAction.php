<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GreenAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'impact_points',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'impact_points' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function userLogs(): HasMany
    {
        return $this->hasMany(UserActionLog::class);
    }
}
