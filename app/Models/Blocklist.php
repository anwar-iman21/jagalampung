<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blocklist extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['expires_at' => 'datetime'];

    public static function isBlocked(string ...$hashes): bool
    {
        return static::whereIn('value_hash', $hashes)
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->exists();
    }
}
