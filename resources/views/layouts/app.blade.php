<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SwiftCart')</title>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen antialiased">
    @include('components.Navbar', ['variant' => 'default'])
    <x-loading />
    <main class="min-h-[80vh]">
        @yield('content')
    </main>
    @include('components.Footer')
    <x-toast />
    <script>
        (() => {
            const hidePreloader = () => {
                const preloader = document.getElementById('global-preloader');
                if (preloader && preloader.style.display !== 'none') {
                    preloader.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => {
                        preloader.style.display = 'none';
                    }, 400);
                }
            };
            window.addEventListener('load', () => setTimeout(hidePreloader, 300));
            setTimeout(hidePreloader, 2500);
        })();
    </script>
    @stack('scripts')
</body>

</html>