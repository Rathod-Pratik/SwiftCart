@extends('layouts.Dashboard')

@section('content')
    @php($categoryId = request()->query('_id'))

    <x-slot name="title">{{ $categoryId ? 'Edit Category' : 'Create Category' }}</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-[#3d6f6d]">Category management</p>
                <h1 id="page-title" class="text-2xl font-bold text-gray-900">
                    {{ $categoryId ? 'Edit Category' : 'Create Category' }}
                </h1>
                <p id="page-description" class="mt-1 text-sm text-gray-600">
                    {{ $categoryId ? 'Update this category.' : 'Add a new category to your store.' }}
                </p>
            </div>

            <a href="{{ route('admin.category') }}"
               class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Cancel
            </a>
        </div>

        <div class="max-w-3xl rounded-xl bg-white p-5 shadow-sm sm:p-8">
            <form id="category-form"
                  method="POST"
                  action="{{ url('/api/categories') }}"
                  data-category-id="{{ $categoryId }}"
                  class="flex flex-col gap-6">
                @csrf
                <div id="form-status" class="hidden rounded-lg px-4 py-3 text-sm" role="alert"></div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-gray-700">Name</label>
                        <input id="name"
                               name="name"
                               type="text"
                               value="{{ old('name') }}"
                               required
                               maxlength="255"
                               placeholder="e.g. Electronics"
                               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="mb-2 block text-sm font-semibold text-gray-700">Slug</label>
                        <input id="slug"
                               name="slug"
                               type="text"
                               value="{{ old('slug') }}"
                               required
                               maxlength="255"
                               placeholder="e.g. electronics"
                               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="icon" class="mb-2 block text-sm font-semibold text-gray-700">Icon</label>
                        <input id="icon"
                               name="icon"
                               type="text"
                               value="{{ old('icon') }}"
                               required
                               maxlength="100"
                               placeholder="e.g. lucide-shopping-bag"
                               class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        @error('icon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">Status</label>
                        <select id="status"
                                name="status"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                            <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-gray-700">Description</label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              required
                              maxlength="255"
                              placeholder="Describe this category"
                              class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.category') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#315b59] focus:outline-none focus:ring-2 focus:ring-[#3d6f6d]/30">
                        <span id="submit-label">{{ $categoryId ? 'Save Changes' : 'Create Category' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (() => {
                const form = document.querySelector('#category-form');
                const categoryId = form.dataset.categoryId;
                const apiUrl = @json(url('/api/categories'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());
                const status = document.querySelector('#form-status');
                const submitButton = form.querySelector('button[type="submit"]');
                const submitLabel = document.querySelector('#submit-label');

                const showStatus = (message, isError = false) => {
                    status.textContent = message;
                    status.className = `rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                };

                const setFieldValue = (name, value) => {
                    const field = form.elements[name];
                    if (field && !field.value) field.value = value ?? '';
                };

                const loadCategory = async () => {
                    try {
                        const response = await fetch(`${apiUrl}/${encodeURIComponent(categoryId)}`, {
                            headers: { Accept: 'application/json' },
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            throw new Error(payload.message || 'Unable to load category.');
                        }

                        const category = payload.data;
                        setFieldValue('name', category.name);
                        setFieldValue('slug', category.slug);
                        setFieldValue('icon', category.icon);
                        setFieldValue('description', category.description);
                        form.elements.status.value = category.status;
                    } catch (error) {
                        showStatus(error.message, true);
                        submitButton.disabled = true;
                    }
                };

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    submitButton.disabled = true;
                    submitLabel.textContent = categoryId ? 'Saving...' : 'Creating...';

                    const data = Object.fromEntries(new FormData(form));
                    delete data._token;

                    try {
                        const response = await fetch(categoryId ? `${apiUrl}/${encodeURIComponent(categoryId)}` : apiUrl, {
                            method: categoryId ? 'PATCH' : 'POST',
                            headers: {
                                Accept: 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: JSON.stringify(data),
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            const validationMessage = payload.errors
                                ? Object.values(payload.errors).flat().join(' ')
                                : payload.message;
                            throw new Error(validationMessage || 'Unable to save category.');
                        }

                        window.location.href = @json(route('admin.category'));
                    } catch (error) {
                        showStatus(error.message, true);
                        submitButton.disabled = false;
                        submitLabel.textContent = categoryId ? 'Save Changes' : 'Create Category';
                    }
                });

                if (categoryId) loadCategory();
            })();
        </script>
    @endpush
@endsection