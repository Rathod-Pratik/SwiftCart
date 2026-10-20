<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Vendor Panel' }} | SwiftCart</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f7fbe9] antialiased">
    <x-navbar variant="vendor" />
    <x-sidebar variant="vendor" />

    <main class="min-h-screen pt-20 lg:ml-64">
        <div class="min-h-screen p-3 sm:p-5">
            <div class="min-h-[calc(100vh-7rem)] rounded-2xl border border-[#8cb920]/20 bg-white shadow-sm">
                @yield('content')
            </div>
        </div>
    </main>

    @stack('scripts')
</body>
</html>
