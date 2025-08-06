<?php

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use LaravelPlus\VersionPlatformManager\Services\VersionService;

class VersionAcknowledgmentController extends Controller
{
    public function __construct(
        private VersionService $versionService
    ) {}

    /**
     * Mark version as read by the user.
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'version' => 'required|string',
        ]);

        $user = auth()->user();
        $version = $validated['version'];

        // Update user's version
        $this->versionService->updateUserVersion($user, $version);
        
        // Mark as seen
        $this->versionService->markVersionAsSeen($user, $version);

        // Set cookie to prevent showing again for 1 day
        $cookie = cookie('version_' . $version . '_read', 'true', 60 * 24); // 1 day

        return response()->json([
            'success' => true,
            'message' => 'Version marked as read',
        ])->withCookie($cookie);
    }

    /**
     * Skip version (user doesn't want to read it now).
     */
    public function skip(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'version' => 'required|string',
        ]);

        $version = $validated['version'];

        // Set cookie to prevent showing again for 1 day
        $cookie = cookie('version_' . $version . '_skipped', 'true', 60 * 24); // 1 day

        return response()->json([
            'success' => true,
            'message' => 'Version skipped',
        ])->withCookie($cookie);
    }

    /**
     * Check if user should see version update.
     */
    public function shouldShowUpdate(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['should_show' => false]);
        }

        $latestVersion = $this->versionService->getLatestPlatformVersion();
        
        if (!$latestVersion) {
            return response()->json(['should_show' => false]);
        }

        // Check if user needs update
        $needsUpdate = $this->versionService->userNeedsUpdate($user);
        
        // Check if already read or skipped via cookie
        $readCookie = $request->cookie('version_' . $latestVersion->version . '_read');
        $skippedCookie = $request->cookie('version_' . $latestVersion->version . '_skipped');
        
        $shouldShow = $needsUpdate && !$readCookie && !$skippedCookie;

        return response()->json([
            'should_show' => $shouldShow,
            'version' => $latestVersion->version,
            'title' => $latestVersion->title,
            'description' => $latestVersion->description,
            'markdown_content' => $latestVersion->getWhatsNewMarkdownAttribute(),
        ]);
    }
} 