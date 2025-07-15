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
        
        @if($latestVersion && $userVersion->isOlderThan($latestVersion->version))
            <div class="text-left mt-6 space-y-6">
                <!-- System Update Section -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">
                        System Update from Version {{ $userVersion->version }} to {{ $latestVersion->version }}
                    </h3>
                    <p class="text-blue-800">{{ $latestVersion->description ?? 'We\'ve made significant improvements to enhance your experience!' }}</p>
                </div>

                <!-- What's New Features -->
                @if($whatsNew->count() > 0)
                    <div class="space-y-4">
                        @foreach($whatsNew->groupBy('platform_version.version') as $version => $features)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="text-lg font-semibold text-gray-900 mb-3">🔒 What Does This Mean for You?</h5>
                                <ul class="space-y-2">
                                    @foreach($features as $feature)
                                        <li class="flex items-start space-x-2">
                                            <span class="text-lg">{{ $feature->type_icon }}</span>
                                            <div>
                                                <strong class="text-gray-900">{{ $feature->getTitleFromContent() }}:</strong>
                                                <span class="text-gray-700">{{ $feature->getContentWithoutTitle() ?: $feature->content }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Signature Section -->
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <p class="text-green-800 mb-2">We're excited for you to experience these updates.</p>
                    <p class="text-green-800">If you have any questions or comments, please don't hesitate to reach out.</p>
                    <p class="text-green-900 font-semibold mt-3">{!! config('version-platform-manager.whats_new_signature') !!}</p>
                </div>

                <!-- Mark as Read Button -->
                <div class="flex justify-center mt-8">
                    <form action="{{ route('version-platform-manager.whats-new.mark-read') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="version_id" value="{{ $latestVersion->id }}">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Mark as Read
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="text-center mt-6">
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                    <h3 class="text-lg font-semibold text-green-900 mb-2">You're up to date!</h3>
                    <p class="text-green-800">You're currently running the latest version ({{ $userVersion->version }}) of our platform.</p>
                </div>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" id="success-message">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('success-message').style.display = 'none';
        }, 3000);
    </script>
@endif
@endsection 