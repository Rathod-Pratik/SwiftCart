@extends('layouts.Dashboard')
@section('content')
    <x-slot name='title'>Vendor Request</x-slot>
    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Vendor Request
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    View all vendor applications and their status.
                </p>
            </div>
            <a href="{{ route('admin.vendor') }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Back
            </a>
        </div>
       
        <div class=" rounded-xl bg-white p-4 shadow-sm sm:p-6">
             <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <label class="relative block w-full sm:max-w-sm" for="vendor-search">
                <span class="sr-only">Search vendors</span>
                <input id="vendor-search" type="search" placeholder="Search by name, email, or company..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
            </label>
            <p 
                class="text-sm font-semibold text-gray-700">
              3 Request
            </p>
        </div>
            <div class='overflow-x-auto'>
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="border-b border-gray-200 text-xs uppercase tracking-wide text-gray-500">
                        <tr>
                            <th class="px-4 py-3 font-semibod">Vendor Name</th>
                            <th class="px-4 py-3 font-semibod">Email</th>
                            <th class="px-4 py-3 font-semibod">Phone</th>
                            <th class="px-4 py-3 font-semibod">Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="vendor-list" class="divide-y divide-gray-100">
                        <tr id="vendor-loading">
                            <td colspan="5" class="px-4 py-12 text-center hidden">
                                <x-Loading />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="vendor-empty" class="hidden py-12 text-center text-sm text-gray-500">
                No vendor requests found.

            </div>
            <div
                class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                <p id="pagination-summary" class="text-sm text-gray-500"></p>
                <div id="pagination" class="flex gap-2"></div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const apiUrl = "{{ route('admin.vendor.request') }}";
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const list = document.getElementById('vendor-list');
            const emptyState = document.getElementById('vendor-empty');
            const loading = document.getElementById('vendor-loading');
            const pagination = document.getElementById('pagination');
            const searchInput = document.getElementById('search');
            const paginationSummary = document.getElementById('pagination-summary');

            let currentPage = 1;
            let searchTimer;

            const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            } [character]));
        </script>
    @endpush
@endsection
