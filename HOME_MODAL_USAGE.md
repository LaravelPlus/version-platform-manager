# Using Modal Popup with Whats-New Component

This guide shows you how to use the modal wrapper to display the whats-new component as a popup on your home.blade.php page.

## Option 1: Simple Modal Wrapper

Add this to your `home.blade.php` file:

```blade
<!-- Include the modal wrapper -->
<x-version-platform-manager::modal-wrapper id="whats-new-modal" :autoShow="true">
    <!-- Your whats-new component inside the modal -->
    <x-version-platform-manager::whats-new :autoShow="false"></x-version-platform-manager::whats-new>
    
    <!-- Optional footer with buttons -->
    <x-slot name="footer">
        <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            Got it!
        </button>
        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Close
        </button>
    </x-slot>
</x-version-platform-manager::modal-wrapper>
```

## Option 2: Manual Trigger Modal

If you want to trigger the modal manually (e.g., with a button):

```blade
<!-- Button to trigger modal -->
<button onclick="showModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
    Show Updates
</button>

<!-- Modal wrapper -->
<x-version-platform-manager::modal-wrapper id="whats-new-modal" :autoShow="false">
    <x-version-platform-manager::whats-new :autoShow="false"></x-version-platform-manager::whats-new>
    
    <x-slot name="footer">
        <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            Got it!
        </button>
    </x-slot>
</x-version-platform-manager::modal-wrapper>
```

## Option 3: Custom Styled Modal

For a more customized appearance:

```blade
<x-version-platform-manager::modal-wrapper id="announcement-modal" :autoShow="true" maxWidth="max-w-4xl">
    <div class="text-center">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4 -4" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
            🎉 Exciting News: 🎉 <br> Major Updates to Improve Your Experience!
        </h2>
        
        <!-- Your whats-new component -->
        <x-version-platform-manager::whats-new :autoShow="false"></x-version-platform-manager::whats-new>
        
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded mt-6">
            <p class="text-green-800 mb-2">We're excited for you to experience these updates.</p>
            <p class="text-green-800">If you have any questions or comments, please don't hesitate to reach out.</p>
            <p class="text-green-900 font-semibold mt-3">Best regards,<br>Kitio Internacional d.o.o.</p>
        </div>
    </div>
    
    <x-slot name="footer">
        <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
            Got it!
        </button>
        <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
            Close
        </button>
    </x-slot>
</x-version-platform-manager::modal-wrapper>
```

## Configuration Options

### Modal Wrapper Props:
- `id`: Unique identifier for the modal (default: 'modal-wrapper')
- `show`: Whether to show the modal initially (default: false)
- `autoShow`: Whether to auto-show the modal after page load (default: false)
- `maxWidth`: Tailwind max-width class (default: 'max-w-2xl')

### Available Functions:
- `showModal()`: Show the modal
- `closeModal()`: Close the modal

## Features:
- ✅ Auto-shows after page load (if autoShow is true)
- ✅ Closes when clicking outside
- ✅ Closes with Escape key
- ✅ Responsive design
- ✅ Customizable width
- ✅ Optional footer with buttons
- ✅ Works with any content, including whats-new component

## Example: Complete home.blade.php

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Welcome to Our Platform</h1>
    
    <!-- Your existing content here -->
    
    <!-- Modal with whats-new component -->
    <x-version-platform-manager::modal-wrapper id="whats-new-modal" :autoShow="true">
        <x-version-platform-manager::whats-new :autoShow="false"></x-version-platform-manager::whats-new>
        
        <x-slot name="footer">
            <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                Got it!
            </button>
        </x-slot>
    </x-version-platform-manager::modal-wrapper>
</div>
@endsection
``` 