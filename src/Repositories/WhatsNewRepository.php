<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Repositories;

use LaravelPlus\VersionPlatformManager\Contracts\WhatsNewRepositoryInterface;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use Illuminate\Support\Collection;

class WhatsNewRepository implements WhatsNewRepositoryInterface
{
    /**
     * Get all WhatsNew entries with platform version relationship.
     */
    public function all(): Collection
    {
        return WhatsNew::with('platformVersion')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get all active WhatsNew entries.
     */
    public function active(): Collection
    {
        return WhatsNew::where('is_active', true)
            ->with('platformVersion')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Find WhatsNew entry by ID with platform version relationship.
     */
    public function findById(int $id): ?WhatsNew
    {
        return WhatsNew::with('platformVersion')->find($id);
    }

    /**
     * Get WhatsNew entries for a specific platform version.
     */
    public function forPlatformVersion(string $version): Collection
    {
        return WhatsNew::whereHas('platformVersion', function ($query) use ($version) {
            $query->where('version', $version);
        })->with('platformVersion')->orderBy('sort_order')->get();
    }

    /**
     * Get WhatsNew entries for a specific platform version ID.
     */
    public function forPlatformVersionId(int $platformVersionId): Collection
    {
        return WhatsNew::where('platform_version_id', $platformVersionId)
            ->with('platformVersion')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get WhatsNew entries by type.
     */
    public function byType(string $type): Collection
    {
        return WhatsNew::where('type', $type)
            ->with('platformVersion')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get WhatsNew entries by status.
     */
    public function byStatus(bool $isActive): Collection
    {
        return WhatsNew::where('is_active', $isActive)
            ->with('platformVersion')
            ->orderBy('sort_order')
            ->get();
    }

    /**
     * Get all platform versions for dropdowns.
     */
    public function getAllPlatformVersions(): Collection
    {
        return PlatformVersion::orderBy('version', 'desc')->get();
    }

    /**
     * Create a new WhatsNew entry.
     */
    public function create(array $data): WhatsNew
    {
        return WhatsNew::create($data);
    }

    /**
     * Update an existing WhatsNew entry.
     */
    public function update(WhatsNew $whatsNew, array $data): bool
    {
        return $whatsNew->update($data);
    }

    /**
     * Delete a WhatsNew entry.
     */
    public function delete(WhatsNew $whatsNew): bool
    {
        return $whatsNew->delete();
    }

    /**
     * Get the next sort order for a platform version.
     */
    public function getNextSortOrder(int $platformVersionId): int
    {
        $maxOrder = WhatsNew::where('platform_version_id', $platformVersionId)
            ->max('sort_order');
        
        return ($maxOrder ?? 0) + 1;
    }

    /**
     * Bulk create WhatsNew entries from parsed data.
     */
    public function bulkCreate(array $entries, int $platformVersionId): int
    {
        $created = 0;
        
        foreach ($entries as $entry) {
            $entry['platform_version_id'] = $platformVersionId;
            $entry['sort_order'] = $entry['sort_order'] ?? $this->getNextSortOrder($platformVersionId);
            
            if ($this->create($entry)) {
                $created++;
            }
        }
        
        return $created;
    }
} 