<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use LaravelPlus\VersionPlatformManager\Services\VersionService;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use Illuminate\Support\Facades\Route;

class WhatsNewPageController extends Controller
{
    public function __construct(
        private VersionService $versionService
    ) {
        $this->middleware('auth');
    }

    /**
     * Display the changelog page.
     */
    public function index(): View
    {
        // Check if user is authenticated, if not redirect to login
        // if (!auth()->check()) {
        //     return redirect()->route('login');
        // }

        $user = auth()->user();
        $userVersion = $this->versionService->getUserVersion($user);
        
        // Get only published versions with their published features, ordered by release date descending
        $versions = PlatformVersion::with(['whatsNew' => function($query) {
            $query->where('status', 'published')->orderBy('sort_order', 'asc');
        }])
        ->where('is_active', true) // Only show published versions
        ->orderBy('released_at', 'desc')
        ->orderBy('version', 'desc')
        ->get();

        // Get the latest version for comparison
        $latestVersion = $versions->first();

        return view('version-platform-manager::whats-new.page', compact(
            'userVersion',
            'latestVersion',
            'versions'
        ));
    }

    /**
     * Mark the current version as seen by the user.
     */
    public function markAsRead(Request $request): RedirectResponse
    {
        // Check if user is authenticated, if not redirect to login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'version_id' => 'required|integer|exists:platform_versions,id',
        ]);

        $user = auth()->user();
        $version = PlatformVersion::find($validated['version_id']);
        
        if ($version) {
            // Update the user's actual version to the latest version
            $this->versionService->updateUserVersion($user, $version->version);
            
            // Also mark as seen for tracking
            $this->versionService->markVersionAsSeen($user, $version->version);
            
            // Log for debugging
            \Log::info('User marked version as read', [
                'user_id' => $user->id,
                'version' => $version->version,
                'version_id' => $version->id
            ]);
        }

        // Redirect back to whats-new page after marking as read
        return redirect()->route('version-platform-manager.whats-new.public')->with('success', 'Marked as read successfully.');
    }
} 