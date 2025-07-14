<?php
declare(strict_types=1);

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Services\VersionService;

class VersionController extends Controller
{
    public function __construct(
        private VersionService $versionService
    ) {}

    /**
     * Display a listing of platform versions.
     */
    public function index(): View
    {
        $versions = PlatformVersion::orderBy('version', 'desc')->paginate(20);
        $statistics = $this->versionService->getVersionStatistics();

        return view('version-platform-manager::admin.versions.index', compact('versions', 'statistics'));
    }

    /**
     * Show the form for creating a new platform version.
     */
    public function create(): View
    {
        return view('version-platform-manager::admin.versions.create');
    }

    /**
     * Store a newly created platform version.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'version' => 'required|string|unique:platform_versions,version',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'released_at' => 'nullable|date',
        ]);

        $this->versionService->createPlatformVersion($validated);

        return redirect()->route('version-manager.versions.index')
            ->with('success', 'Platform version created successfully.');
    }

    /**
     * Show the form for editing the specified platform version.
     */
    public function edit(PlatformVersion $version): View
    {
        return view('version-platform-manager::admin.versions.edit', compact('version'));
    }

    /**
     * Update the specified platform version.
     */
    public function update(Request $request, PlatformVersion $version): RedirectResponse
    {
        $validated = $request->validate([
            'version' => 'required|string|unique:platform_versions,version,' . $version->id,
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'released_at' => 'nullable|date',
        ]);

        $version->update($validated);

        return redirect()->route('version-manager.versions.index')
            ->with('success', 'Platform version updated successfully.');
    }

    /**
     * Remove the specified platform version.
     */
    public function destroy(PlatformVersion $version): RedirectResponse
    {
        $version->delete();

        return redirect()->route('version-manager.versions.index')
            ->with('success', 'Platform version deleted successfully.');
    }

    /**
     * Mark a version as seen by the current user.
     */
    public function markSeen(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'version' => 'required|string',
        ]);

        $this->versionService->markVersionAsSeen(auth()->user(), $validated['version']);

        return redirect()->back()->with('success', 'Version marked as seen.');
    }
} 