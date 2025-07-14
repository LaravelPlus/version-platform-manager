<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Repositories;

use LaravelPlus\VersionPlatformManager\Contracts\WhatsNewRepositoryInterface;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;
use Illuminate\Support\Collection;

class WhatsNewRepository implements WhatsNewRepositoryInterface
{
    public function all(): Collection
    {
        return WhatsNew::all();
    }

    public function active(): Collection
    {
        return WhatsNew::where('is_active', true)->get();
    }

    public function findById(int $id): ?WhatsNew
    {
        return WhatsNew::find($id);
    }

    public function forPlatformVersion(string $version): Collection
    {
        return WhatsNew::whereHas('platformVersion', function ($query) use ($version) {
            $query->where('version', $version);
        })->get();
    }

    public function create(array $data): WhatsNew
    {
        return WhatsNew::create($data);
    }

    public function update(WhatsNew $whatsNew, array $data): bool
    {
        return $whatsNew->update($data);
    }

    public function delete(WhatsNew $whatsNew): bool
    {
        return $whatsNew->delete();
    }
} 