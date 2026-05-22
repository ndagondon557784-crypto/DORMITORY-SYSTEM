<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Role constants — use these everywhere, never hardcode strings
    const ROLE_ADMIN   = 'admin';
    const ROLE_STUDENT = 'student';

    /*
     * CRITICAL: 'role' MUST be in $fillable.
     * If it is missing, User::create(['role' => 'admin']) will silently
     * be ignored due to mass-assignment protection, and every user
     * will have role = null, breaking isAdmin() and isStudent().
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      // <-- DO NOT REMOVE THIS
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Role helpers ──────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    // ── Relationships ─────────────────────────────────────────────

    public function student(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Student::class);
    }

    public function activityLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}