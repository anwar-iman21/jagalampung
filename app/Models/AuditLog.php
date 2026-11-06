<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['meta' => 'array'];

    public static function record(string $action, ?Model $entity = null, array $meta = []): void
    {
        static::create([
            'user_id' => auth()->id(), 'action' => $action,
            'entity' => $entity ? class_basename($entity) : null, 'entity_id' => $entity?->getKey(),
            'meta' => $meta, 'ip_hash' => hash('sha256', request()->ip().config('app.key')),
        ]);
    }
}
