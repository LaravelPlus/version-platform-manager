<?php

namespace LaravelPlus\VersionPlatformManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVersion extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'user_versions';

    protected $fillable = [
        'user_id',
        'version',
        'last_seen_version',
        'last_seen_at',
        'metadata',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user this version belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the platform version this user version relates to.
     */
    public function platformVersion(): BelongsTo
    {
        return $this->belongsTo(PlatformVersion::class, 'version', 'version');
    }

    /**
     * Scope to get users with version older than the given version.
     */
    public function scopeOlderThan($query, string $version)
    {
        return $query->where('version', '<', $version);
    }

    /**
     * Scope to get users with version newer than the given version.
     */
    public function scopeNewerThan($query, string $version)
    {
        return $query->where('version', '>', $version);
    }

    /**
     * Check if the user's version is older than the given version.
     */
    public function isOlderThan(string $version): bool
    {
        return version_compare($this->version, $version, '<');
    }

    /**
     * Check if the user's version is newer than the given version.
     */
    public function isNewerThan(string $version): bool
    {
        return version_compare($this->version, $version, '>');
    }

    /**
     * Update the last seen version.
     */
    public function updateLastSeen(string $version): void
    {
        $this->update([
            'last_seen_version' => $version,
            'last_seen_at' => now(),
        ]);
    }

    /**
     * Get the version number as an integer for comparison.
     */
    public function getVersionNumberAttribute(): int
    {
        return (int) str_replace('.', '', $this->version);
    }
} 