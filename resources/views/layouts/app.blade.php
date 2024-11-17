<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <title>{{ $title ?? config('app.name', 'Invooicy') }}</title>

    <!-- Linking the local Font Awesome CSS file -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome/all.min.css') }}">
    <!-- Linking the local fonts file -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/fontawesome/all.min.css', 'resources/js/app.js', 'resources\css\fonts.css'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="m-4">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
