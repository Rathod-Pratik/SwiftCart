<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','SwiftCart')</title>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased">
   @include('components.Navbar', ['variant' => 'default'])

    <main class="min-h-[80vh]">
        @yield('content')
    </main>
    @include('components.Footer')
</body>
</html>