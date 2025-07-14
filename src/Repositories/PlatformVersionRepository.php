<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Repositories;

use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use Illuminate\Support\Collection;
use LaravelPlus\VersionPlatformManager\Contracts\PlatformVersionRepositoryInterface;

class PlatformVersionRepository implements PlatformVersionRepositoryInterface
{
    public function all(): Collection
    {
        return PlatformVersion::all();
    }

    public function active(): Collection
    {
        return PlatformVersion::where('is_active', true)->get();
    }

    public function findByVersion(string $version): ?PlatformVersion
    {
        return PlatformVersion::where('version', $version)->first();
    }

    public function latest(): ?PlatformVersion
    {
        return PlatformVersion::active()->orderBy('version', 'desc')->first();
    }

    public function paginate(int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return PlatformVersion::orderBy('version', 'desc')->paginate($perPage);
    }
} 