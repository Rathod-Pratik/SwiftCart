@extends('layouts.Dashboard')

@section('content')
    @php($categoryId = request()->query('_id'))

    <x-slot name="title">{{ $categoryId ? 'Edit Category' : 'Create Category' }}</x-slot>

    <div class="p-4 sm:p-6">
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
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
                  enctype="multipart/form-data"
                  novalidate
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
                        <p id="name-error" class="mt-1 text-sm text-red-600 {{ $errors->has('name') ? '' : 'hidden' }}">@error('name'){{ $message }}@enderror</p>
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
                        <p id="slug-error" class="mt-1 text-sm text-red-600 {{ $errors->has('slug') ? '' : 'hidden' }}">@error('slug'){{ $message }}@enderror</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="image" class="mb-2 block text-sm font-semibold text-gray-700">Category image</label>
                        <input id="image"
                               name="image"
                               type="file"
                               accept="image/jpeg,image/png,image/webp"
                               {{ $categoryId ? '' : 'required' }}
                               class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition file:mr-4 file:rounded-md file:border-0 file:bg-[#3d6f6d] file:px-3 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-[#315b59] focus:border-[#3d6f6d] focus:ring-2 focus:ring-[#3d6f6d]/20">
                        <div id="current-image" class="mt-3 hidden items-center gap-3">
                            <img id="current-image-preview" class="h-16 w-16 rounded-lg border border-gray-200 object-cover" alt="Current category image">
                            <span class="text-xs text-gray-500">Choose a new image to replace the current one.</span>
                        </div>
                        <p id="image-error" class="mt-1 text-sm text-red-600 {{ $errors->has('image') ? '' : 'hidden' }}">@error('image'){{ $message }}@enderror</p>
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
                        <p id="status-error" class="mt-1 text-sm text-red-600 {{ $errors->has('status') ? '' : 'hidden' }}">@error('status'){{ $message }}@enderror</p>
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
                    <p id="description-error" class="mt-1 text-sm text-red-600 {{ $errors->has('description') ? '' : 'hidden' }}">@error('description'){{ $message }}@enderror</p>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.category') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-[#3d6f6d] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#315b59] focus:outline-none focus:ring-2 focus:ring-[#3d6f6d]/30">
                        <span id="submit-label">{{ $categoryId ? 'Save Changes' : 'Create' }}</span>
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
                const storageUrl = @json(asset('storage'));
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? @json(csrf_token());
                const status = document.querySelector('#form-status');
                const submitButton = form.querySelector('button[type="submit"]');
                const submitLabel = document.querySelector('#submit-label');
                const fieldNames = ['name', 'slug', 'image', 'status', 'description'];

                const showStatus = (message, isError = false) => {
                    status.textContent = message;
                    status.className = `rounded-lg px-4 py-3 text-sm ${isError ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700'}`;
                };

                const setFieldValue = (name, value) => {
                    const field = form.elements[name];
                    if (field && !field.value) field.value = value ?? '';
                };

                const clearFieldErrors = () => {
                    fieldNames.forEach((name) => {
                        const error = document.querySelector(`#${name}-error`);
                        error.textContent = '';
                        error.classList.add('hidden');
                    });
                };

                const showFieldErrors = (errors) => {
                    Object.entries(errors ?? {}).forEach(([name, messages]) => {
                        const error = document.querySelector(`#${name}-error`);
                        if (!error) return;
                        error.textContent = Array.isArray(messages) ? messages.join(' ') : messages;
                        error.classList.remove('hidden');
                    });
                };

                const validateRequiredFields = () => {
                    const errors = {};
                    ['name', 'slug', 'status', 'description'].forEach((name) => {
                        if (!String(form.elements[name].value ?? '').trim()) {
                            errors[name] = ['This field is required.'];
                        }
                    });
                    if (!categoryId && !form.elements.image.files.length) {
                        errors.image = ['A category image is required.'];
                    }
                    showFieldErrors(errors);
                    return Object.keys(errors).length === 0;
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
                        const currentImage = document.querySelector('#current-image');
                        const currentImagePreview = document.querySelector('#current-image-preview');
                        if (category.icon) {
                            currentImagePreview.src = category.icon.startsWith('http')
                                ? category.icon
                                : `${storageUrl}/${category.icon}`;
                            currentImagePreview.onerror = () => currentImage.classList.add('hidden');
                            currentImage.classList.remove('hidden');
                            currentImage.classList.add('flex');
                        } else {
                            currentImage.classList.add('hidden');
                        }
                        setFieldValue('description', category.description);
                        form.elements.status.value = category.status;
                    } catch (error) {
                        showStatus(error.message, true);
                        submitButton.disabled = true;
                    }
                };

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    clearFieldErrors();
                    if (!validateRequiredFields()) return;
                    submitButton.disabled = true;
                    submitLabel.textContent = categoryId ? 'Saving...' : 'Creating...';

                    const data = new FormData(form);
                    data.delete('_token');
                    if (categoryId) data.append('_method', 'PATCH');

                    try {
                        const response = await fetch(categoryId ? `${apiUrl}/${encodeURIComponent(categoryId)}` : apiUrl, {
                            method: 'POST',
                            headers: {
                                Accept: 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                            },
                            body: data,
                        });
                        const payload = await response.json();
                        if (!response.ok || !payload.success) {
                            showFieldErrors(payload.errors);
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