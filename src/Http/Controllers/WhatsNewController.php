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
    public function index()
    {
        $whatsNew = $this->whatsNewRepository->all();
        $versions = $this->whatsNewRepository->getAllPlatformVersions();

        return view('version-platform-manager::admin.whats-new.index', compact('whatsNew', 'versions'));
    }

    /**
     * Show the form for creating new what's new content.
     */
    public function create()
    {
        $versions = $this->whatsNewRepository->getAllPlatformVersions();

        return view('version-platform-manager::admin.whats-new.create', compact('versions'));
    }

    /**
     * Store a newly created what's new content.
     */
    public function store(StoreWhatsNewRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Set default sort order if not provided
        if (empty($validated['sort_order'])) {
            $validated['sort_order'] = $this->whatsNewRepository->getNextSortOrder($validated['platform_version_id']);
        }

        $this->whatsNewRepository->create($validated);

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
    public function update(UpdateWhatsNewRequest $request, WhatsNew $whatsNew): RedirectResponse
    {
        $validated = $request->validated();

        $this->whatsNewRepository->update($whatsNew, $validated);

        return redirect()->route('version-manager.whats-new.index')
            ->with('success', 'What\'s new content updated successfully.');
    }

    /**
     * Remove the specified what's new content.
     */
    public function destroy(WhatsNew $whatsNew): RedirectResponse
    {
        $this->whatsNewRepository->delete($whatsNew);

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
        foreach ($entries as $entry) {
            $md .= "## {$entry->title}\n";
            $md .= "**Type:** " . ucfirst($entry->type) . "\n\n";
            $md .= trim($entry->content) . "\n\n";
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

        // Parse Markdown: ## Title, **Type:**, then content until next ## or end
        $pattern = '/^##\s*(.+)\n\*\*Type:\*\*\s*(\w+)\n+([\s\S]*?)(?=^##\s|\z)/mU';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
        
        $entries = [];
        foreach ($matches as $match) {
            [$full, $title, $type, $entryContent] = $match;
            $entryContent = trim($entryContent);
            if (!$title || !$type || !$entryContent) continue;
            
            $entries[] = [
                'title' => $title,
                'content' => $entryContent,
                'type' => strtolower($type),
                'is_active' => true,
            ];
        }
        
        $imported = $this->whatsNewRepository->bulkCreate($entries, $version->id);
        
        return redirect()->route('version-manager.whats-new.index')
            ->with('success', "Imported $imported entries from Markdown.");
    }
} 