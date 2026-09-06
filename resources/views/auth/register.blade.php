@extends('layouts.app')
@section('title', 'Sign Up')
@section('content')
<div class="min-h-[80vh] bg-[#e6f0f1] text-gray-900 flex justify-center">
        <div class="max-w-7xl m-0 sm:m-10 bg-white shadow sm:rounded-lg flex justify-center flex-1">
            <div data-aos="fade-right" class="flex-1 bg-[#b8d8da] text-center hidden lg:flex items-center justify-center">
                <div class="m-12 xl:m-16 w-full h-105 bg-contain bg-center bg-no-repeat flex items-center justify-center"
                    style="background-image: url({{ asset('images/login.webp') }});">
                </div>
            </div>
            <div data-aos="fade-left" class="lg:w-1/2 xl:w-5/12 p-6 sm:p-12 flex items-center">
                <div class="w-full flex flex-col items-center">
                    <h1 class="text-2xl xl:text-3xl font-extrabold">
                        SignUp Now
                    </h1>
                    <div class="w-full flex-1 mt-8 flex justify-center items-center">
                        <form method="POST" id="SignUp" class="w-full max-w-xs gap-3 flex flex-col items-center">
                            <x-input
                                name='name'
                                type="text" placeholder="Name" />
                            <x-input
                                required
                                name='email'
                                type="email" placeholder="Email" />
                            <x-input
                                required
                                name='Password'
                                type="password" placeholder="Password" />

                            <x-button type="submit" class="bg-[#d09523] hover:bg-[#f4b942] cursor-pointer mt-5  font-semibold  text-gray-100 w-full py-4 rounded-lg  transition-all duration-300 ease-in-out flex items-center justify-center focus:shadow-outline focus:outline-none">
                                Sign Up
                            </x-button>

                            <button>
                                <span class="ml-3 text-white">
                                    Sign Up
                                </span>
                            </button>
                            <p class="text-xs text-gray-600 text-center">
                                Already have Account ?
                                <a href="/login" class="border-b border-gray-500 border-dotted">
                                    Login
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
