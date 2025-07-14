<?php

namespace LaravelPlus\VersionPlatformManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsNew extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'platform_version_id',
        'title',
        'content',
        'type',
        'is_active',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Get the platform version this content belongs to.
     */
    public function platformVersion(): BelongsTo
    {
        return $this->belongsTo(PlatformVersion::class);
    }

    /**
     * Scope to get only active content.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get content by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to order by sort order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get the feature type label.
     */
    public function getTypeLabelAttribute(): string
    {
        $types = config('version-platform-manager.feature_types', []);
        return $types[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get the feature type icon.
     */
    public function getTypeIconAttribute(): string
    {
        $icons = [
            'feature' => '🎉',
            'improvement' => '⚡',
            'bugfix' => '🐛',
            'security' => '🔒',
            'deprecation' => '⚠️',
        ];

        return $icons[$this->type] ?? '📝';
    }
} 