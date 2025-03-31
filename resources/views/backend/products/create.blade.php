@extends('backend.dashboard')
@section('backend_content')
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">Create New Product</h2>

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
            <form action="{{ route('backend.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <span>Category</span>
                            <select name="category_id" id="category_id"
                                class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                required>
                                <option value="">Select Category</option>
                                @foreach (\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name}}
                                    </option>
                                @endforeach
                            </select>
                        </label>
                        @error('category_id')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <span>Subcategory</span>
                            <select name="subcategory_id" id="subcategory_id"
                                class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                required>
                                <option value="">Select Subcategory</option>
                                <!-- Subcategories will be loaded via AJAX -->
                            </select>
                        </label>
                        @error('subcategory_id')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Name</span>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                            placeholder="Product Name" required>
                    </label>
                    @error('name')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Base Price</span>
                        <input type="number" name="base_price" value="{{ old('base_price') }}" step="0.01"
                            min="0"
                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                            placeholder="0.00" required>
                    </label>
                    @error('base_price')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Description</span>
                        <textarea name="description" id="summernote"
                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                            rows="6" placeholder="Product Description">{{ old('description') }}</textarea>
                    </label>
                    @error('description')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Main Image</span>
                        <input type="file" name="main_image" accept="image/*"
                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                    </label>
                    @error('main_image')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Gallery Images</span>
                        <input type="file" name="gallery_images[]" multiple accept="image/*"
                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        <p class="text-xs text-gray-500 mt-1">You can select up to 5 images</p>
                    </label>
                    @error('gallery_images')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                    @error('gallery_images.*')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Status</span>
                        <select name="status"
                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </label>
                    @error('status')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Create Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {
            // Store the old subcategory ID if it exists (for form validation errors)
            const oldSubcategoryId = "{{ old('subcategory_id') }}";

            // Function to load subcategories via AJAX
            function loadSubcategories(categoryId) {
                if (!categoryId) {
                    $('#subcategory_id').html('<option value="">Select Subcategory</option>');
                    return;
                }

                // Show loading state
                $('#subcategory_id').html('<option value="">Loading...</option>');
                $('#subcategory_id').prop('disabled', true);

                // Make AJAX request using jQuery
                $.ajax({
                    url: '/get-subcategories/' + categoryId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Reset and re-enable the subcategory select
                        let options = '<option value="">Select Subcategory</option>';

                        // Add the subcategories
                        if (data.length > 0) {
                            $.each(data, function(index, subcategory) {
                                // Select the previously selected subcategory if any
                                const selected = (oldSubcategoryId == subcategory.id) ?
                                    'selected' : '';
                                options +=
                                    `<option value="${subcategory.id}" ${selected}>${subcategory.subcategory_name}</option>`;
                            });
                        } else {
                            options = '<option value="">No subcategories available</option>';
                        }

                        $('#subcategory_id').html(options);
                        $('#subcategory_id').prop('disabled', false);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading subcategories:', error);
                        $('#subcategory_id').html(
                            '<option value="">Error loading subcategories</option>');
                        $('#subcategory_id').prop('disabled', false);
                    }
                });
            }

            // Load subcategories when category changes
            $('#category_id').on('change', function() {
                loadSubcategories($(this).val());
            });

            // Initial load if category is already selected (e.g., on validation error)
            if ($('#category_id').val()) {
                loadSubcategories($('#category_id').val());
            }
        });
    </script>
@endsection
