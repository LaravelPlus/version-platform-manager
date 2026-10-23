<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use LaravelPlus\VersionPlatformManager\Contracts\UserRepositoryInterface;

final class UserController extends Controller
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        $users = User::with(['userVersion'])
            ->orderBy('name')
            ->paginate(20);

        // Get latest platform version for comparison
        $latestVersion = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::orderBy('version', 'desc')->first();

        return view('version-platform-manager::admin.users.index', compact('users', 'latestVersion'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        return view('version-platform-manager::admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:admin,user',
        ]);
        if (!Schema::hasColumn('users', 'role')) {
            unset($validated['role']); // package migrations don't create it
        }
        $validated['password'] = bcrypt($validated['password']);
        $this->userRepository->create($validated);

        return redirect()->route(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($user): View
    {
        $user = $this->userRepository->find((int) $user);

        return view('version-platform-manager::admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $user): RedirectResponse
    {
        $user = $this->userRepository->find((int) $user);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'nullable|string|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        if (!Schema::hasColumn('users', 'role')) {
            unset($validated['role']); // package migrations don't create it
        }
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        $this->userRepository->update($user, $validated);

        return redirect()->route(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($user): RedirectResponse
    {
        $user = $this->userRepository->find((int) $user);
        $this->userRepository->delete($user);

        return redirect()->route(config('version-platform-manager.admin.route_name_prefix', 'version-manager') . '.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
