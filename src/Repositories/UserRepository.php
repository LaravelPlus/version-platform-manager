<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Repositories;

use LaravelPlus\VersionPlatformManager\Contracts\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function all(): Collection
    {
        return User::all();
    }

    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return User::paginate($perPage);
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
} 