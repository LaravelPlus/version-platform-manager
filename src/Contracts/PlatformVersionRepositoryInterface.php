<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Contracts;

use Illuminate\Support\Collection;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;

interface PlatformVersionRepositoryInterface
{
    public function all(): Collection;

    public function active(): Collection;

    public function findByVersion(string $version): ?PlatformVersion;

    public function latest(): ?PlatformVersion;

    public function paginate(int $perPage = 20): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
}
