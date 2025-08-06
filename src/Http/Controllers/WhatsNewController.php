<?php

namespace LaravelPlus\VersionPlatformManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use LaravelPlus\VersionPlatformManager\Models\WhatsNew;
use LaravelPlus\VersionPlatformManager\Models\PlatformVersion;
use LaravelPlus\VersionPlatformManager\Repositories\WhatsNewRepository;
use LaravelPlus\VersionPlatformManager\Http\Requests\WhatsNew\StoreWhatsNewRequest;
use LaravelPlus\VersionPlatformManager\Http\Requests\WhatsNew\UpdateWhatsNewRequest;
use LaravelPlus\VersionPlatformManager\Http\Requests\WhatsNew\ImportMarkdownRequest;

class WhatsNewController extends Controller
{
    public function __construct(
        private WhatsNewRepository $whatsNewRepository
    ) {}

    /**
     * Display a listing of what's new content.
     */
    public function index(Request $request)
    {
        $query = WhatsNew::with('platformVersion')->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($request->filled('version')) {
            $query->where('platform_version_id', $request->version);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $features = $query->paginate(20);
        $versions = PlatformVersion::orderBy('version', 'desc')->get();
        
        // Statistics
        $totalFeatures = WhatsNew::count();
        $publishedFeatures = WhatsNew::where('status', 'published')->count();
        $draftFeatures = WhatsNew::where('status', 'draft')->count();
        $totalVersions = PlatformVersion::count();

        return view('version-platform-manager::admin.whats-new.index', compact(
            'features', 
            'versions', 
            'totalFeatures', 
            'publishedFeatures', 
            'draftFeatures', 
            'totalVersions'
        ));
    }

    /**
     * Show the form for creating new what's new content.
     */
    public function create(Request $request)
    {
        $versionId = $request->get('version_id');
        $version = null;
        
        if ($versionId) {
            $version = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::find($versionId);
        }
        
        $versions = $this->whatsNewRepository->getAllPlatformVersions();

        return view('version-platform-manager::admin.whats-new.create', compact('versions', 'version'));
    }

    /**
     * Store a newly created what's new content.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform_version_id' => 'required|exists:platform_versions,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'nullable|in:feature,improvement,bugfix,security,deprecation',
            'sort_order' => 'nullable|integer',
        ]);
        
        // Set default status to draft
        $validated['status'] = 'draft';
        
        // Set default sort order if not provided
        if (empty($validated['sort_order'])) {
            $validated['sort_order'] = $this->whatsNewRepository->getNextSortOrder($validated['platform_version_id']);
        }

        $feature = $this->whatsNewRepository->create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feature created successfully.',
                'feature' => $feature
            ]);
        }

        // If we have a version_id in the request, redirect back to the version show page
        if ($request->has('platform_version_id')) {
            $version = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::find($request->platform_version_id);
            if ($version) {
                return redirect()->route('version-manager.versions.show', $version)
                    ->with('success', 'Feature created successfully.');
            }
        }

        return redirect()->route('version-manager.whats-new.index')
            ->with('success', 'What\'s new content created successfully.');
    }

    /**
     * Show the form for editing the specified what's new content.
     */
    public function edit(WhatsNew $whatsNew)
    {
        $versions = $this->whatsNewRepository->getAllPlatformVersions();

        return view('version-platform-manager::admin.whats-new.edit', compact('whatsNew', 'versions'));
    }

    /**
     * Update the specified what's new content.
     */
    public function update(Request $request, WhatsNew $whatsNew)
    {
        $validated = $request->validate([
            'platform_version_id' => 'required|exists:platform_versions,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'nullable|in:feature,improvement,bugfix,security,deprecation',
            'sort_order' => 'nullable|integer',
        ]);
        
        // Keep existing status if not changing
        $validated['status'] = $whatsNew->status;

        $this->whatsNewRepository->update($whatsNew, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feature updated successfully.',
                'feature' => $whatsNew->fresh()
            ]);
        }

        // If we have a version_id in the request, redirect back to the version show page
        if ($request->has('platform_version_id')) {
            $version = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::find($request->platform_version_id);
            if ($version) {
                return redirect()->route('version-manager.versions.show', $version)
                    ->with('success', 'Feature updated successfully.');
            }
        }

        return redirect()->route('version-manager.whats-new.index')
            ->with('success', 'What\'s new content updated successfully.');
    }

    /**
     * Remove the specified what's new content.
     */
    public function destroy(Request $request, WhatsNew $whatsNew)
    {
        $this->whatsNewRepository->delete($whatsNew);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feature deleted successfully.'
            ]);
        }

        // If we have a version_id in the request, redirect back to the version show page
        if ($request->has('platform_version_id')) {
            $version = \LaravelPlus\VersionPlatformManager\Models\PlatformVersion::find($request->platform_version_id);
            if ($version) {
                return redirect()->route('version-manager.versions.show', $version)
                    ->with('success', 'Feature deleted successfully.');
            }
        }

        return redirect()->route('version-manager.whats-new.index')
            ->with('success', 'What\'s new content deleted successfully.');
    }

    /**
     * Export all what's new entries for a given version as a Markdown file.
     */
    public function exportMarkdown(Request $request, $platformVersionId)
    {
        $version = PlatformVersion::findOrFail($platformVersionId);
        $entries = $this->whatsNewRepository->forPlatformVersionId($platformVersionId);

        $md = "# What's New for Version {$version->version}\n\n";
        $md .= "**Version:** {$version->version}\n";
        $md .= "**Title:** {$version->title}\n";
        $md .= "**Released:** " . ($version->released_at ? $version->released_at->format('Y-m-d') : 'Not set') . "\n\n";
        
        if ($entries->count() === 0) {
            $md .= "*No features have been added to this version yet.*\n\n";
        } else {
            foreach ($entries as $entry) {
                $md .= "## {$entry->title}\n";
                $md .= "**Type:** " . ucfirst($entry->type ?? 'feature') . "\n";
                $md .= "**Status:** " . ucfirst($entry->status ?? 'draft') . "\n\n";
                $md .= trim($entry->content) . "\n\n";
                $md .= "---\n\n";
            }
        }

        $filename = 'whats-new-' . $version->version . '-' . date('Y-m-d_H-i-s') . '.md';
        return response($md)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Import what's new entries for a given version from a Markdown file.
     */
    public function importMarkdown(ImportMarkdownRequest $request, $platformVersionId)
    {
        $version = PlatformVersion::findOrFail($platformVersionId);
        $content = file_get_contents($request->file('markdown_file')->getRealPath());

        // Log the content for debugging
        \Log::info('Importing markdown content', ['content' => $content]);

        // Parse Markdown: ## Title, **Type:**, **Status:**, then content until next ## or end
        $pattern = '/^##\s*(.+?)\s*\n\*\*Type:\*\*\s*(\w+)(?:\s*\n\*\*Status:\*\*\s*(\w+))?\s*\n+([\s\S]*?)(?=^##\s|\z)/mU';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        \Log::info('Regex matches found', ['count' => count($matches), 'matches' => $matches]);
        
        $entries = [];
        foreach ($matches as $match) {
            [$full, $title, $type, $status, $entryContent] = $match;
            $entryContent = trim($entryContent);
            if (!$title || !$type || !$entryContent) {
                \Log::warning('Skipping invalid entry', ['title' => $title, 'type' => $type, 'content_length' => strlen($entryContent)]);
                continue;
            }
            
            // Validate and normalize status
            $normalizedStatus = strtolower(trim($status ?? 'draft'));
            if (!in_array($normalizedStatus, ['draft', 'private', 'published'])) {
                $normalizedStatus = 'draft';
            }
            
            // Validate and normalize type
            $normalizedType = strtolower(trim($type));
            if (!in_array($normalizedType, ['feature', 'improvement', 'bugfix', 'security', 'deprecation'])) {
                $normalizedType = 'feature';
            }
            
            $entries[] = [
                'title' => trim($title),
                'content' => $entryContent,
                'type' => $normalizedType,
                'status' => $normalizedStatus,
                'platform_version_id' => $version->id,
            ];
        }
        
        \Log::info('Processed entries', ['count' => count($entries), 'entries' => $entries]);
        
        $imported = 0;
        $errors = [];
        foreach ($entries as $entry) {
            try {
                WhatsNew::create($entry);
                $imported++;
                \Log::info('Successfully imported feature', ['title' => $entry['title']]);
            } catch (\Exception $e) {
                $error = 'Failed to import feature "' . $entry['title'] . '": ' . $e->getMessage();
                \Log::error($error);
                $errors[] = $error;
            }
        }
        
        $message = "Successfully imported $imported features from Markdown file.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode('; ', $errors);
        }
        
        return redirect()->route('version-manager.versions.show', $version)
            ->with('success', $message);
    }
} 