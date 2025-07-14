<?php

namespace LaravelPlus\VersionPlatformManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlatformVersion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'version',
        'title',
        'description',
        'is_active',
        'released_at',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'released_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the what's new content for this version.
     */
    public function whatsNew(): HasMany
    {
        return $this->hasMany(WhatsNew::class);
    }

    /**
     * Get active what's new content for this version.
     */
    public function activeWhatsNew(): HasMany
    {
        return $this->hasMany(WhatsNew::class)->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Scope to get only active versions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get versions released after a specific date.
     */
    public function scopeReleasedAfter($query, $date)
    {
        return $query->where('released_at', '>', $date);
    }

    /**
     * Get the version number as an integer for comparison.
     */
    public function getVersionNumberAttribute(): int
    {
        return (int) str_replace('.', '', $this->version);
    }

    /**
     * Check if this version is newer than the given version.
     */
    public function isNewerThan(string $version): bool
    {
        return version_compare($this->version, $version, '>');
    }

    /**
     * Check if this version is older than the given version.
     */
    public function isOlderThan(string $version): bool
    {
        return version_compare($this->version, $version, '<');
    }
} 