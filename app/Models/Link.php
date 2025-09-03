<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Link extends Model
{
    protected $table = 'links';
    protected $fillable = [
        'user_id',
        'token',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for active links.
     * A link is considered active if:
     * 1. It has is_active set to true
     * 2. It has not expired (expires_at is in the future)
     * 3. If token is provided, it matches the provided token
     */
    public function scopeActive(Builder $query, ?string $token = null): Builder
    {
        $query = $query->where('is_active', true)
                       ->where('expires_at', '>=', now());

        if ($token) {
            $query->where('token', $token);
        }

        return $query;
    }

}
