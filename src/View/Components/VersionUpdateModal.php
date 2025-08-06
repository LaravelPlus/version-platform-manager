<?php

namespace LaravelPlus\VersionPlatformManager\View\Components;

use Illuminate\View\Component;

class VersionUpdateModal extends Component
{
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('version-platform-manager::components.version-update-modal');
    }
} 