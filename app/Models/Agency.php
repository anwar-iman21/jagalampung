<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['categories' => 'array'];

    public static function forCategory(string $category): ?self
    {
        return static::all()->first(fn ($a) => in_array($category, $a->categories ?? [], true));
    }
}
