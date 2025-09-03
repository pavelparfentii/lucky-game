<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    protected $guarded = [];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatestThree(Builder $query, User $user): Builder
    {
        $query->where('user_id', $user->id)
        ->latest()
        ->take(3);
        return $query;
    }
}
