<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    // =========================================================================
    // HELPER METHODS - Cek Role
    // =========================================================================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isBidan(): bool
    {
        return $this->role === 'bidan';
    }

    public function isDokter(): bool
    {
        return $this->role === 'dokter';
    }

    public function isPasien(): bool
    {
        return $this->role === 'pasien';
    }

    /**
     * Cek apakah user memiliki salah satu role yang diberikan.
     *
     * @param string ...$roles
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles);
    }

    // =========================================================================
    // RELATIONSHIPS
    // =========================================================================

    /**
     * Relasi ke data pasien (jika user ini role-nya 'pasien').
     */
    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class);
    }
}
