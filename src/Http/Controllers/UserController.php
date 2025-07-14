<?php

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(): View
    {
        // In a real application, you would fetch users from the database
        // For now, we'll return the view with mock data
        return view('version-platform-manager::admin.users.index');
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
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|in:admin,user',
            'current_version' => 'required|string',
        ]);

        // In a real application, you would create the user
        // For now, we'll redirect back with a success message
        return redirect()->route('version-manager.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($user): View
    {
        // In a real application, you would fetch the user from the database
        return view('version-platform-manager::admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $user)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user,
            'role' => 'required|string|in:admin,user',
            'current_version' => 'required|string',
        ]);

        // In a real application, you would update the user
        // For now, we'll redirect back with a success message
        return redirect()->route('version-manager.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($user)
    {
        // In a real application, you would delete the user
        // For now, we'll redirect back with a success message
        return redirect()->route('version-manager.users.index')
            ->with('success', 'User deleted successfully.');
    }
} 