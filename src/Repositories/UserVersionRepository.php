<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Repositories;

use LaravelPlus\VersionPlatformManager\Contracts\UserVersionRepositoryInterface;
use LaravelPlus\VersionPlatformManager\Models\UserVersion;
use Illuminate\Support\Collection;

class UserVersionRepository implements UserVersionRepositoryInterface
{
    public function all(): Collection
    {
        return UserVersion::all();
    }

    public function findByUserId(int $userId): ?UserVersion
    {
        return UserVersion::where('user_id', $userId)->first();
    }

    public function create(array $data): UserVersion
    {
        return UserVersion::create($data);
    }

    public function update(UserVersion $userVersion, array $data): bool
    {
        return $userVersion->update($data);
    }

    public function delete(UserVersion $userVersion): bool
    {
        return $userVersion->delete();
    }

    public function olderThan(string $version): Collection
    {
        return UserVersion::where('version', '<', $version)->get();
    }

    public function newerThan(string $version): Collection
    {
        return UserVersion::where('version', '>', $version)->get();
    }
} 