@extends('backend.dashboard')
@section('backend_content')
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Product: {{ $product->name }}</h2>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Product Information Form -->
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
            <form action="{{ route('backend.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
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
                        <input type="text" name="name" value="{{ old('name', $product->name) }}"
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
                        <input type="number" name="base_price" value="{{ old('base_price', $product->base_price) }}"
                            step="0.01" min="0"
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
                            rows="6" placeholder="Product Description">{{ old('description', $product->description) }}</textarea>
                    </label>
                    @error('description')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Current Main Image</span>
                    </label>
                    @if ($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                            class="w-32 h-32 object-cover rounded mb-2">
                    @else
                        <p class="text-gray-500">No main image</p>
                    @endif
                    <label class="block text-sm font-medium text-gray-700 mt-2">
                        <span>Change Main Image</span>
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
                    </label>
                    <div class="flex flex-wrap gap-2 mb-2">
                        @foreach ($product->images->where('is_primary', false) as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery image"
                                    class="w-24 h-24 object-cover rounded">
                            </div>
                        @endforeach
                    </div>
                    <label class="block text-sm font-medium text-gray-700 mt-2">
                        <span>Add Gallery Images</span>
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
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                    </label>
                    @error('status')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Update Product
                    </button>
                </div>
            </form>
        </div>

        <!-- Product Attributes Section -->
        <h2 class="my-6 text-xl font-semibold text-gray-700">Product Attributes</h2>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Current Attributes -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Current Attributes</h3>
                    @if ($product->attributes->isEmpty())
                        <p class="text-gray-500">No attributes added yet</p>
                    @else
                        @foreach ($product->attributes as $attribute)
                            <div class="mb-4 p-3 border rounded-lg">
                                <div class="flex justify-between items-center">
                                    <h4 class="font-semibold">{{ $attribute->name }}</h4>
                                    <form
                                        action="{{ route('backend.products.attributes.remove', ['product' => $product->id, 'attribute' => $attribute->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this attribute? This will also delete any variations using this attribute.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">Values:</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach ($attribute->values as $value)
                                        <span
                                            class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">{{ $value->value }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if (!$product->attributes->isEmpty())
                        <div class="mt-4">
                            <a href="{{ route('backend.products.variations.create', $product->id) }}"
                                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-md active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                                Manage Variations
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Add New Attribute -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold">Add New Attribute</h3>
                    <form action="{{ route('backend.products.attributes.add', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                <span>Attribute Name</span>
                                <input type="text" name="name"
                                    class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                    placeholder="e.g. Size, Color, Material" required>
                            </label>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                <span>Attribute Values</span>
                                <input type="text" name="values"
                                    class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                    placeholder="e.g. Small, Medium, Large (comma separated)" required>
                            </label>
                            <p class="text-xs text-gray-500 mt-1">Enter values separated by commas</p>
                        </div>
                        <div>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                Add Attribute
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product Variations Section -->
        @if ($product->variations->isNotEmpty())
            <h2 class="my-6 text-xl font-semibold text-gray-700">Product Variations</h2>
            <div class="w-full overflow-hidden rounded-lg shadow-xs">
                <div class="w-full overflow-x-auto">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3">Attributes</th>
                                <th class="px-4 py-3">SKU</th>
                                <th class="px-4 py-3">Price</th>
                                <th class="px-4 py-3">Stock</th>
                                <th class="px-4 py-3">Default</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y">
                            @foreach ($product->variations as $variation)
                                <tr class="text-gray-700">
                                    <td class="px-4 py-3 text-sm">
                                        @foreach ($variation->attributeValues as $attrValue)
                                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded-full">
                                                {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $variation->sku ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $variation->price }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $variation->stock_quantity }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @if ($variation->is_default)
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Yes</span>
                                        @else
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">No</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('backend.products.variations.edit', ['product' => $product->id, 'variation' => $variation->id]) }}"
                                            class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('backend.products.variations.destroy', ['product' => $product->id, 'variation' => $variation->id]) }}"
                                            method="POST" class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this variation? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-3 py-1 ml-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-md active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script>
        $(document).ready(function() {
            // Store the original/current subcategory ID for this product
            const currentSubcategoryId = "{{ old('subcategory_id', $product->subcategory_id) }}";

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
                                const selected = (currentSubcategoryId == subcategory.id) ?
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

            // Initial load if category is already selected (which it should be for edit form)
            if ($('#category_id').val()) {
                loadSubcategories($('#category_id').val());
            }
        });
    </script>
@endsection
