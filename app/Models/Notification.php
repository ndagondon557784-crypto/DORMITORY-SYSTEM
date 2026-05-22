<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'message', 'type', 'link', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function send(int $userId, string $title, string $message, string $type = 'info', string $link = null): void
    {
        static::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'link' => $link,
        ]);
    }

    public static function broadcast(string $title, string $message, string $type = 'info', string $link = null): void
    {
        $users = User::where('is_active', true)->pluck('id');
        foreach ($users as $userId) {
            static::send($userId, $title, $message, $type, $link);
        }
    }
}