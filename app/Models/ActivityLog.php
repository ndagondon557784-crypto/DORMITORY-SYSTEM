<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'ip_address',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Static helpers ────────────────────────────────────────────────────────

    /**
     * Create an activity log entry.
     * Safe to call — will not throw even if table is missing.
     */
    public static function log(
        string  $action,
        string  $description,
        ?string $modelType = null,
        ?int    $modelId   = null
    ): void {
        try {
            static::create([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'model_type'  => $modelType,
                'model_id'    => $modelId,
                'description' => $description,
                'ip_address'  => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // Never let a log failure break the application
        }
    }
}