<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model', 'model_id', 'description', 'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        string $action,
        string $description,
        ?string $model = null,
        ?int $modelId = null
    ): void {
        try {
            static::create([
                'user_id'     => auth()->id(),
                'action'      => $action,
                'model'       => $model,
                'model_id'    => $modelId,
                'description' => $description,
                'ip_address'  => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Never crash the app for a log failure
        }
    }
}