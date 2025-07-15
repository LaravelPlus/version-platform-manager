<?php

namespace LaravelPlus\VersionPlatformManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsNew extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'whats_new';

    protected $fillable = [
        'platform_version_id',
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

    /**
     * Auto-detect type from content emoji.
     */
    public function detectTypeFromContent(): string
    {
        $emojiTypeMap = [
            '🎉' => 'feature',
            '⚡' => 'improvement',
            '🐛' => 'bugfix',
            '🔒' => 'security',
            '⚠️' => 'deprecation',
        ];

        // Get first line and check for emoji
        $lines = explode("\n", $this->content);
        $firstLine = trim($lines[0] ?? '');
        
        foreach ($emojiTypeMap as $emoji => $type) {
            if (str_starts_with($firstLine, $emoji)) {
                return $type;
            }
        }

        return 'feature'; // Default
    }

    /**
     * Extract title from content (first line without emoji).
     */
    public function getTitleFromContent(): string
    {
        $lines = explode("\n", $this->content);
        $firstLine = trim($lines[0] ?? '');
        
        // Remove emoji from start
        $emojiPattern = '/^[🎉⚡🐛🔒⚠️📝]\s*/u';
        $title = preg_replace($emojiPattern, '', $firstLine);
        
        return $title ?: 'Untitled';
    }

    /**
     * Get content without title (for description).
     */
    public function getContentWithoutTitle(): string
    {
        $lines = explode("\n", $this->content);
        
        // Remove first line (title) and return rest
        array_shift($lines);
        
        return trim(implode("\n", $lines));
    }

    /**
     * Set content and auto-detect type.
     */
    public function setContentAttribute($value): void
    {
        $this->attributes['content'] = $value;
        
        // Auto-detect type if not set
        if (empty($this->attributes['type'])) {
            $this->attributes['type'] = $this->detectTypeFromContent();
        }
    }
} 