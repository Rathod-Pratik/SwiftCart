<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Panel' }} | SwiftCart</title>
@fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="bg-white">

    <x-navbar variant="admin" />

    <x-Sidebar variant="admin" />

    <main class="lg:ml-64 pt-20  p-3">
        <div class="rounded-lg bg-[#edf2f7] min-h-screen">
            @yield('content')
        </div>
    </main>

</body>
@stack('scripts')
</html>