<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Typography Plugin -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    
    <!-- Vue 3 CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-center h-16 px-4 border-b border-gray-200">
                <h1 class="text-xl font-bold text-gray-900">Platform Manager</h1>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 mt-8 px-4">
                <div class="space-y-2">
                    @foreach (config('version-platform-manager.navbar_links', []) as $link)
                        @php
                            $isActive = isset($link['route']) ? request()->routeIs($link['route'] . '*') : (request()->is(ltrim($link['url'] ?? '', '/')) ? 'bg-blue-50 text-blue-700' : '');
                        @endphp
                        <a href="{{ isset($link['route']) ? route($link['route']) : url($link['url']) }}"
                           class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover:text-gray-900 {{ $isActive }}"
                           @if(isset($link['target'])) target="{{ $link['target'] }}" @endif>
                            {!! $link['icon'] ?? '' !!}
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </nav>

            <!-- Bottom Navigation -->
            <div class="border-t border-gray-200 p-4 space-y-3">
                <!-- Go Home/Back Button -->
                <a href="{{ route('home') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Go Home
                </a>

                <!-- Logout Button -->
                @auth
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-600 rounded-md hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
                @endauth
            </div>
        </div>

        <!-- Main content -->
        <div class="pl-64">
            <!-- Top navigation -->
            <div class="sticky top-0 z-40 bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <h2 class="text-lg font-medium text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            <div class="flex items-center space-x-3">
                                <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-500">•</span>
                                <span class="text-sm text-gray-500">Admin</span>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html> 