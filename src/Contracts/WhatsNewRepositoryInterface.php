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
    public function create(array $data): WhatsNew;
    public function update(WhatsNew $whatsNew, array $data): bool;
    public function delete(WhatsNew $whatsNew): bool;
} 