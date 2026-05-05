<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class SharedLink extends Model
{
    protected $fillable = [
        'token',
        'shareable_type',
        'shareable_id',
        'created_by',
        'permission',
        'expires_at',
        'access_count',
    ];

    protected $casts = [
        'expires_at'   => 'datetime',
        'access_count' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function shareable(): MorphTo
    {
        return $this->morphTo();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public static function generateToken(): string
    {
        return Str::random(48);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return ! $this->isExpired();
    }

    public function url(): string
    {
        return route('share.show', $this->token);
    }

    public function incrementAccess(): void
    {
        $this->increment('access_count');
    }
}