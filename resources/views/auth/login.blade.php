
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
                        <form class="mx-auto my-auto max-w-xs" method="POST" id="login">
                            <input type="hidden" value="login" name="action">
                            <x-input
                                name="email"
                                type="email"
                                placeholder="Email" />
                            <x-input
                                name="password"
                                type="password"
                                placeholder="Password"
                                class="mt-5" />
                            <x-button
                                type="submit"
                                class="mt-5 w-full py-4">
                                Login
                            </x-button>
                            <p class="mt-6 text-xs text-gray-600 text-center">
                                Don't have Account ?
                                <a href="/signup" class="border-b border-gray-500 border-dotted">
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
@endsection
