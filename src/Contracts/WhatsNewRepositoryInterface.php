<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Contracts;

use LaravelPlus\VersionPlatformManager\Models\WhatsNew;
use Illuminate\Support\Collection;

interface WhatsNewRepositoryInterface
{
    public function all(): Collection;
    public function active(): Collection;
    public function findById(int $id): ?WhatsNew;
    public function forPlatformVersion(string $version): Collection;
    public function forPlatformVersionId(int $platformVersionId): Collection;
    public function byType(string $type): Collection;
    public function byStatus(bool $isActive): Collection;
    public function getAllPlatformVersions(): Collection;
    public function create(array $data): WhatsNew;
    public function update(WhatsNew $whatsNew, array $data): bool;
    public function delete(WhatsNew $whatsNew): bool;
    public function getNextSortOrder(int $platformVersionId): int;
    public function bulkCreate(array $entries, int $platformVersionId): int;
} 