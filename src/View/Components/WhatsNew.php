<?php

namespace LaravelPlus\VersionPlatformManager\View\Components;

use Illuminate\View\Component;
use LaravelPlus\VersionPlatformManager\Services\VersionService;
use Illuminate\Support\Facades\Auth;

class WhatsNew extends Component
{
    public function __construct(
        public ?string $title = null,
        public bool $autoShow = true,
        public bool $dismissible = true
    ) {
        $this->autoShow = config('version-platform-manager.modal.auto_show', true);
        $this->dismissible = config('version-platform-manager.modal.dismissible', true);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        if (!Auth::check()) {
            return view('version-platform-manager::components.empty');
        }

        $versionService = app(VersionService::class);
        $user = Auth::user();

        // Check if user needs to see updates
        if (!$versionService->userNeedsUpdate($user)) {
            return view('version-platform-manager::components.empty');
        }

        // Get what's new content for the user
        $whatsNew = $versionService->getWhatsNewForUser($user);

        if ($whatsNew->isEmpty()) {
            return view('version-platform-manager::components.empty');
        }

        // Get latest version
        $latestVersion = $versionService->getLatestPlatformVersion();

        $data = [
            'whatsNew' => $whatsNew,
            'latestVersion' => $latestVersion,
            'userVersion' => $versionService->getUserVersion($user),
            'title' => $this->title ?: '🎉 Exciting News: 🎉 <br> Major Updates to Improve Your Experience!',
            'autoShow' => $this->autoShow,
            'dismissible' => $this->dismissible,
        ];

        // Debug: Log the data being passed
        \Log::info('WhatsNew component data:', $data);

        return view('version-platform-manager::components.whats-new', $data);
    }
} 