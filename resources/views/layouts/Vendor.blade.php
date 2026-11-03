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
<body class="min-h-screen bg-[#F5F7F4] antialiased">
    <x-navbar variant="vendor" />
    <x-sidebar variant="vendor" />

    <main class="min-h-screen pt-20 lg:ml-64">
        <div class="min-h-screen p-3 sm:p-5">
            <div @class([
                'min-h-[calc(100vh-7rem)] bg-white [&_a]:cursor-pointer [&_button]:cursor-pointer',
                'rounded-2xl border border-[#224e51]/20 shadow-sm' => ! request()->routeIs('vendor.product.details'),
            ])>
                @yield('content')
            </div>
        </div>
    </main>

    <x-toast />

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
    @stack('scripts')
</body>
</html>
