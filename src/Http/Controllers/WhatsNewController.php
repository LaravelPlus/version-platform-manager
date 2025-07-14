<?php

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Services\VersionService;

class WhatsNewController extends Controller
{
    public function __construct(
        private VersionService $versionService
    ) {}

    /**
     * Display a listing of what's new content.
     */
    public function index()
    {
        $whatsNew = WhatsNew::with('platformVersion')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('version-platform-manager::admin.whats-new.index', compact('whatsNew'));
    }

    /**
     * Show the form for creating new what's new content.
     */
    public function create()
    {
        $versions = PlatformVersion::active()->orderBy('version', 'desc')->get();
        $types = config('version-platform-manager.feature_types', []);

        return view('version-platform-manager::admin.whats-new.create', compact('versions', 'types'));
    }

    /**
     * Store a newly created what's new content.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform_version_id' => 'required|exists:platform_versions,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:feature,improvement,bugfix,security,deprecation',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $this->versionService->createWhatsNew($validated);

        return redirect()->route('admin.whats-new.index')
            ->with('success', 'What\'s new content created successfully.');
    }

    /**
     * Show the form for editing the specified what's new content.
     */
    public function edit(WhatsNew $whatsNew)
    {
        $versions = PlatformVersion::active()->orderBy('version', 'desc')->get();
        $types = config('version-platform-manager.feature_types', []);

        return view('version-platform-manager::admin.whats-new.edit', compact('whatsNew', 'versions', 'types'));
    }

    /**
     * Update the specified what's new content.
     */
    public function update(Request $request, WhatsNew $whatsNew): RedirectResponse
    {
        $validated = $request->validate([
            'platform_version_id' => 'required|exists:platform_versions,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:feature,improvement,bugfix,security,deprecation',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $whatsNew->update($validated);

        return redirect()->route('admin.whats-new.index')
            ->with('success', 'What\'s new content updated successfully.');
    }

    /**
     * Remove the specified what's new content.
     */
    public function destroy(WhatsNew $whatsNew): RedirectResponse
    {
        $whatsNew->delete();

        return redirect()->route('admin.whats-new.index')
            ->with('success', 'What\'s new content deleted successfully.');
    }
} 