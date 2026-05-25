<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'target',
        'is_published',
        'expires_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'expires_at'   => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now()->toDateString());
            });
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->isStudent()) {
            return $query->whereIn('target', ['all', 'students']);
        }

        if ($user->isStaff()) {
            return $query->whereIn('target', ['all', 'staff']);
        }

        return $query;
    }
}