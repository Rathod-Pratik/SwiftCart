<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SwiftCart')</title>

    <script>
        try {
            if (sessionStorage.getItem('has_visited')) {
                document.documentElement.classList.add('preloader-hidden');
            }
        } catch (e) {}
    </script>
    <style>
        .preloader-hidden #global-preloader {
            display: none !important;
        }
    </style>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen antialiased">
    @include('components.Navbar', ['variant' => 'default'])
    <x-loading fullscreen />
    <main class="min-h-[80vh]">
        @yield('content')
    </main>
    @include('components.Footer')
    <x-toast />
    <script>
        (() => {
            const preloader = document.getElementById('global-preloader');
            if (!preloader) return;

            let hasVisited = false;
            try {
                hasVisited = Boolean(sessionStorage.getItem('has_visited'));
            } catch (e) {}

            if (hasVisited) {
                preloader.remove();
                return;
            }

            try {
                sessionStorage.setItem('has_visited', 'true');
            } catch (e) {}

            const hidePreloader = () => {
                if (preloader && preloader.style.display !== 'none') {
                    preloader.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => {
                        preloader.remove();
                    }, 400);
                }
            };

            if (document.readyState === 'complete') {
                setTimeout(hidePreloader, 300);
            } else {
                window.addEventListener('load', () => setTimeout(hidePreloader, 300));
            }
            setTimeout(hidePreloader, 2500);
        })();
    </script>
    @stack('scripts')
</body>

</html>