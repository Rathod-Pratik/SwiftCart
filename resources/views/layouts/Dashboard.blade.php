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
<body class="min-h-screen bg-[#F5F7F4] antialiased">

    <x-navbar variant="admin" />

    <x-Sidebar variant="admin" />

    <main class="min-h-screen pt-20 lg:ml-64">
        <div class="min-h-[calc(100vh-5rem)] p-3 sm:p-5">
            <div class="min-h-[calc(100vh-7rem)] rounded-2xl border border-[#8cb920]/20 bg-white shadow-sm">
                @yield('content')
            </div>
        </div>
    </main>

    <x-Toast />

    <script>
        window.Logout = async function() {
            if (!confirm('Are you sure you want to log out?')) return;
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                await fetch('{{ url('/api/logout') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
            } catch (err) {
                console.error('Logout error:', err);
            } finally {
                window.location.href = '{{ route('login') }}';
            }
        };
    </script>
</body>
@stack('scripts')
</html>