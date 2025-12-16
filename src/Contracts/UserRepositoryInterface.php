<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Contracts;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function all(): Collection;

    public function paginate(int $perPage = 20): LengthAwarePaginator;

    public function find(int $id): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): bool;

    public function delete(User $user): bool;
}
