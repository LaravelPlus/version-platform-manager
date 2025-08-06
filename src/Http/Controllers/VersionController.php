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
        $versions = PlatformVersion::with('whatsNew')->orderBy('version', 'desc')->paginate(20);
        $statistics = $this->versionService->getVersionStatistics();

        return view('version-platform-manager::admin.versions.index', compact('versions', 'statistics'));
    }

    /**
     * Show the form for creating a new platform version.
     */
    public function create(): View
    {
        $latestVersion = PlatformVersion::orderBy('version', 'desc')->first();
        $nextVersion = $latestVersion ? $this->incrementVersion($latestVersion->version) : '1.0.0';
        
        // Calculate different increment types
        $nextVersions = [];
        if ($latestVersion) {
            $nextVersions = [
                'patch' => $this->incrementVersion($latestVersion->version, 'patch'),
                'minor' => $this->incrementVersion($latestVersion->version, 'minor'),
                'major' => $this->incrementVersion($latestVersion->version, 'major'),
            ];
        }
        
        return view('version-platform-manager::admin.versions.create', compact('nextVersion', 'nextVersions', 'latestVersion'));
    }

    /**
     * Store a newly created platform version.
     */
    public function store(Request $request): RedirectResponse
    {
        // Debug: Log the request data
        \Log::info('Version creation request:', $request->all());
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'released_at' => 'nullable|date',
                'is_active' => 'nullable|in:0,1,true,false',
                'version_type' => 'nullable|in:patch,minor,major',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            throw $e;
        }

        // Generate version number automatically
        $latestVersion = PlatformVersion::orderBy('version', 'desc')->first();
        $versionType = $request->get('version_type', 'patch');
        $newVersionNumber = $latestVersion ? $this->incrementVersion($latestVersion->version, $versionType) : '1.0.0';
        
        // Remove version_type from validated data as it's not a database column
        unset($validated['version_type']);
        
        $validated['version'] = $newVersionNumber;
        // Convert is_active to boolean
        $validated['is_active'] = in_array($validated['is_active'], ['1', 'true', true], true); // Draft by default

        $version = $this->versionService->createPlatformVersion($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Platform version created successfully.',
                'redirect_url' => route('version-manager.versions.show', $version)
            ]);
        }

        // Always redirect to the specific version show page, not the index
        return redirect()->route('version-manager.versions.show', $version)
            ->with('success', 'Platform version created successfully.');
    }

    /**
     * Increment version number intelligently.
     */
    private function incrementVersion(string $currentVersion, string $type = 'patch'): string
    {
        $parts = explode('.', $currentVersion);
        
        // Ensure we have 3 parts (major.minor.patch)
        while (count($parts) < 3) {
            $parts[] = '0';
        }
        
        // Convert to integers
        $major = (int)$parts[0];
        $minor = (int)$parts[1];
        $patch = (int)$parts[2];
        
        switch ($type) {
            case 'major':
                $major++;
                $minor = 0;
                $patch = 0;
                break;
            case 'minor':
                $minor++;
                $patch = 0;
                break;
            case 'patch':
            default:
                $patch++;
                break;
        }
        
        return "{$major}.{$minor}.{$patch}";
    }

    /**
     * Display the specified platform version.
     */
    public function show(PlatformVersion $version): View
    {
        $features = $version->whatsNew()->orderBy('sort_order')->get();
        return view('version-platform-manager::admin.versions.show', compact('version', 'features'));
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
            'description' => 'nullable|string|max:500',
            'released_at' => 'nullable|date',
            'is_active' => 'boolean',
            'whats_new_markdown' => 'nullable|string|max:2000',
        ]);

        $version->update($validated);

        // Handle What's New markdown content
        if ($request->has('whats_new_markdown')) {
            // Store the markdown content in the version's metadata
            $metadata = $version->metadata ?? [];
            if ($request->whats_new_markdown) {
                $metadata['whats_new_markdown'] = $request->whats_new_markdown;
            } else {
                unset($metadata['whats_new_markdown']);
            }
            $version->update(['metadata' => $metadata]);
        }

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
     * Get the next version number.
     */
    public function getNextVersion(Request $request): JsonResponse
    {
        $type = $request->get('type', 'patch'); // patch, minor, major
        $latestVersion = PlatformVersion::orderBy('version', 'desc')->first();
        $nextVersion = $latestVersion ? $this->incrementVersion($latestVersion->version, $type) : '1.0.0';
        
        return response()->json([
            'next_version' => $nextVersion,
            'latest_version' => $latestVersion ? $latestVersion->version : null,
            'type' => $type
        ]);
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