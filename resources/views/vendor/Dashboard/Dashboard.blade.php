@extends('layouts.Vendor')

@section('content')
    <x-slot name="title">Vendor Dashboard</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-8 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Vendor workspace</p>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600">Manage your products, orders, and store performance.</p>
            </div>
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                View Store
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Total products', 'value' => '0', 'description' => 'Products in your store', 'icon' => 'package'],
                ['label' => 'Pending orders', 'value' => '0', 'description' => 'Orders awaiting action', 'icon' => 'shopping-bag'],
                ['label' => 'Completed orders', 'value' => '0', 'description' => 'Successfully fulfilled', 'icon' => 'circle-check'],
                ['label' => 'Store rating', 'value' => '-', 'description' => 'No ratings yet', 'icon' => 'star'],
            ] as $stat)
                <div class="rounded-xl border border-gray-100 bg-[#f8fafd] p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500">{{ $stat['label'] }}</p>
                        <x-dynamic-component :component="'lucide-'.$stat['icon']" class="h-5 w-5 text-[#3d6f6d]" />
                    </div>
                    <p class="mt-4 text-3xl font-bold text-gray-900">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $stat['description'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <section class="rounded-xl border border-gray-100 p-5">
                <h2 class="text-lg font-bold text-gray-900">Quick actions</h2>
                <p class="mt-1 text-sm text-gray-500">Common tasks for your store.</p>
                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <a href="#" class="rounded-lg border border-gray-200 p-4 transition hover:border-[#3d6f6d] hover:bg-[#f8fafd]">
                        <x-lucide-package-plus class="h-5 w-5 text-[#3d6f6d]" />
                        <p class="mt-3 text-sm font-semibold text-gray-900">Add a product</p>
                        <p class="mt-1 text-xs text-gray-500">List a new item in your store.</p>
                    </a>
                    <a href="#" class="rounded-lg border border-gray-200 p-4 transition hover:border-[#3d6f6d] hover:bg-[#f8fafd]">
                        <x-lucide-clipboard-list class="h-5 w-5 text-[#3d6f6d]" />
                        <p class="mt-3 text-sm font-semibold text-gray-900">View orders</p>
                        <p class="mt-1 text-xs text-gray-500">Review and manage recent orders.</p>
                    </a>
                </div>
            </section>

            <section class="rounded-xl border border-gray-100 p-5">
                <h2 class="text-lg font-bold text-gray-900">Getting started</h2>
                <p class="mt-1 text-sm text-gray-500">Complete these steps to start selling.</p>
                <ol class="mt-5 flex flex-col gap-4">
                    @foreach (['Add your first product' => 'Give customers something to discover.', 'Complete your profile' => 'Keep your business information current.', 'Manage incoming orders' => 'Track fulfillment from one place.'] as $title => $description)
                        <li class="flex items-start gap-3">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#3d6f6d] text-xs font-bold text-white">{{ $loop->iteration }}</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $title }}</p>
                                <p class="mt-1 text-xs text-gray-500">{{ $description }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>
        </div>
    </div>
@endsection
