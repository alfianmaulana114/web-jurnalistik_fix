<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
            @php
                $rn = \Illuminate\Support\Facades\Route::currentRouteName();
                $isPublic = str_starts_with($rn, 'home') || str_starts_with($rn, 'news.') || str_starts_with($rn, 'public.');
                $shouldShowAdminNav = auth()->check() && !$isPublic;
            @endphp
        <div class="min-h-screen {{ $isPublic ? 'bg-white' : 'bg-gray-100' }}">
            @if($shouldShowAdminNav)
                @include('layouts.navigation')
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @unless(request()->routeIs('home'))
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        @include('partials.flash')
                    </div>
                @endunless
                @yield('content')
            </main>
        </div>
    </body>
</html>
