<?php

declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use LaravelPlus\VersionPlatformManager\Services\VersionService;
use Illuminate\Support\Facades\Route;

class PublicWhatsNewController extends Controller
{
    public function __construct(
        private VersionService $versionService
    ) {}

    /**
     * Display the public whats-new page.
     */
    public function index(): View
    {
        // Check if user is authenticated, if not redirect to login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userVersion = $this->versionService->getUserVersion($user);
        $latestVersion = $this->versionService->getLatestPlatformVersion();
        $whatsNew = $this->versionService->getWhatsNewForUser($user);

        return view('version-platform-manager::whats-new.page', compact(
            'userVersion',
            'latestVersion',
            'whatsNew'
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
        $version = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::find($validated['version_id']);
        
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

        // Always redirect to home route after marking as read
        return redirect()->route('home')->with('success', 'Marked as read successfully.');
    }
} 