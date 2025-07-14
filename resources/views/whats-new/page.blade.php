@extends('version-platform-manager::layouts.app')

@section('title', 'What\'s New')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8">
    <div class="text-center">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
            <svg class="h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4 -4" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">🎉 Exciting News: 🎉</h1>
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Major Updates to Improve Your Experience!</h2>
        <div class="text-left mt-6 space-y-6">
            {{-- Example static content, replace with dynamic as needed --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <h3 class="text-lg font-semibold text-blue-900 mb-2">
                    System Update from Version 1.0.0 to 2.0.0
                </h3>
                <p class="text-blue-800">We\'ve made significant improvements to enhance your experience!</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h5 class="text-lg font-semibold text-gray-900 mb-3">🔒 What Does This Mean for You?</h5>
                <ul class="space-y-2">
                    <li class="flex items-start space-x-2">
                        <span class="text-lg">📝</span>
                        <div>
                            <strong class="text-gray-900">New Feature:</strong>
                            <span class="text-gray-700">You can now enjoy a faster dashboard and improved analytics.</span>
                        </div>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-lg">🐞</span>
                        <div>
                            <strong class="text-gray-900">Bug Fix:</strong>
                            <span class="text-gray-700">Resolved login issues for some users.</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                <p class="text-green-800 mb-2">We\'re excited for you to experience these updates.</p>
                <p class="text-green-800">If you have any questions or comments, please don\'t hesitate to reach out.</p>
                <p class="text-green-900 font-semibold mt-3">{!! config('version-platform-manager.whats_new_signature') !!}</p>
            </div>
        </div>
    </div>
</div>
@endsection 