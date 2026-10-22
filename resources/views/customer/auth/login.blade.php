@extends('layouts.app')
@section('title', 'Login')
@section('content')
    <div class="min-h-[80vh] bg-[#e6f0f1] text-gray-900 flex justify-center">
        <div class="max-w-7xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
            <div data-aos="fade-right" class="flex-1 bg-[#b8d8da] text-center hidden lg:flex">
                <div class="m-12 xl:m-16 w-full bg-contain bg-center bg-no-repeat"
                    style="background-image: url('{{ asset('images/Login.webp') }}');">
                </div>
            </div>
            <div data-aos="fade-left" class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12 flex justify-center items-center">
                <div class="mt-12 flex flex-col items-center">
                    <h1 class="text-2xl xl:text-3xl font-extrabold">
                        Login Now
                    </h1>
                    <div class="w-full flex-1 mt-8">
                        <form class="mx-auto my-auto max-w-xs" method="POST" action="{{ route('login') }}" id="login">
                            @csrf
                            <div class="w-full">
                                <x-input id="email" autocomplete="email" name="email" type="email"
                                    placeholder="Email" aria-describedby="email-error" />
                                @error('email')
                                    <p id="email-error" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @else
                                    <p id="email-error" class="hidden mt-2 text-sm text-red-600"></p>
                                @enderror
                            </div>
                            <div class="w-full">
                                <x-input autocomplete="current-password" name="password" type="password"
                                    placeholder="Password" class="mt-5" aria-describedby="password-error" />
                                @error('password')
                                    <p id="password-error" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @else
                                    <p id="password-error" class="hidden mt-2 text-sm text-red-600"></p>
                                @enderror
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <label for="remember" class="flex items-center gap-2 cursor-pointer select-none text-xs text-gray-600 hover:text-gray-900">
                                    <input id="remember" name="remember" type="checkbox" value="1" {{ old('remember') ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-[#d09523] focus:ring-[#d09523] cursor-pointer">
                                    <span class="font-medium">Remember me</span>
                                </label>
                            </div>
                            <p id="login-error" class="hidden mt-4 text-sm text-red-600" role="alert"></p>
                            <x-button type="submit" class="mt-5 w-full py-4">
                                Login
                            </x-button>
                            <p class="mt-6 text-xs text-gray-600 text-center">
                                Don't have Account ?
                                <a href="/register" class="border-b border-gray-500 border-dotted">
                                    Sign Up
                                </a>
                                now
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const form = document.querySelector('#login');

        form?.addEventListener('submit', async (event) => {
            event.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            const errorMessage = form.querySelector('#login-error');

            const fields = {
                email: form.querySelector('[name="email"]'),
                password: form.querySelector('[name="password"]'),
            };

            const clearFieldError = (name) => {
                const input = fields[name];
                const error = form.querySelector(`#${name}-error`);

                if (error) {
                    error.textContent = '';
                    error.classList.add('hidden');
                }

                input?.classList.remove('border-red-500', 'focus:border-red-500');
                input?.removeAttribute('aria-invalid');
            };

            const showFieldError = (name, message) => {
                const input = fields[name];
                const error = form.querySelector(`#${name}-error`);

                if (!input || !error) return;

                error.textContent = message;
                error.classList.remove('hidden');

                input.classList.add('border-red-500', 'focus:border-red-500');
                input.setAttribute('aria-invalid', 'true');
            };

            const showLoginError = (message) => {
                errorMessage.textContent = message;
                errorMessage.classList.remove('hidden');
            };

            Object.keys(fields).forEach(clearFieldError);
            errorMessage.textContent = '';
            errorMessage.classList.add('hidden');

            submitButton.disabled = true;
            submitButton.classList.add('opacity-60', 'cursor-not-allowed');

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: new FormData(form),
                    credentials: 'same-origin',
                });

                const payload = await response.json();

                if (!response.ok) {
                    if (payload.errors) {
                        Object.entries(payload.errors).forEach(([field, messages]) => {
                            if (fields[field]) {
                                showFieldError(field, messages[0]);
                            }
                        });

                        if (!Object.keys(payload.errors).some((field) => fields[field])) {
                            showLoginError(payload.message || 'Please check your input and try again.');
                        }

                        return;
                    }

                    showLoginError(payload.message || 'Unable to log in. Please try again.');
                    return;
                }

                window.location.assign(
                    payload.user?.role === 'admin'
                        ? @json(route('admin.dashboard'))
                        : payload.user?.role === 'vendor'
                            ? @json(route('vendor.dashboard'))
                            : @json(route('home'))
                );
            } catch {
                showLoginError('Something went wrong. Please try again.');
            } finally {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        });

        ['email', 'password'].forEach((name) => {
            const input = form?.querySelector(`[name="${name}"]`);

            input?.addEventListener('input', () => {
                const error = form.querySelector(`#${name}-error`);

                if (error && !error.classList.contains('hidden')) {
                    error.textContent = '';
                    error.classList.add('hidden');
                    input.classList.remove('border-red-500', 'focus:border-red-500');
                    input.removeAttribute('aria-invalid');
                }
            });
        });
    </script>
@endsection
