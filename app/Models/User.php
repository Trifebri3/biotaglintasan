<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'avatar_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isBioTagSuperAdmin(): bool
    {
        return $this->role === 'biotag_super_admin';
    }

    public function isLintasanAdmin(): bool
    {
        return $this->role === 'lintasan_admin';
    }

    public function isSchoolAdmin(): bool
    {
        return $this->role === 'school_admin';
    }

    public function isOfficer(): bool
    {
        return $this->role === 'officer';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function schoolMemberships(): HasMany
    {
        return $this->hasMany(SchoolMembership::class);
    }

    public function adoptions(): HasMany
    {
        return $this->hasMany(Adoption::class);
    }

    public function adoptedTrees(): HasManyThrough
    {
        return $this->hasManyThrough(Tree::class, Adoption::class, 'user_id', 'id', 'id', 'tree_id');
    }

    public function actionLogs(): HasMany
    {
        return $this->hasMany(UserActionLog::class);
    }

    public function emissionEntries(): HasMany
    {
        return $this->hasMany(EmissionEntry::class);
    }

    public function observations(): HasMany
    {
        return $this->hasMany(TreeObservation::class, 'observed_by');
    }
}
