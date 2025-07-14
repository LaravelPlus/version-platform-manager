<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Contracts;

use LaravelPlus\VersionPlatformManager\Models\UserVersion;
use Illuminate\Support\Collection;

interface UserVersionRepositoryInterface
{
    public function all(): Collection;
    public function findByUserId(int $userId): ?UserVersion;
    public function create(array $data): UserVersion;
    public function update(UserVersion $userVersion, array $data): bool;
    public function delete(UserVersion $userVersion): bool;
    public function olderThan(string $version): Collection;
    public function newerThan(string $version): Collection;
} 