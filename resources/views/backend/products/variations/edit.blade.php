@extends('backend.dashboard')
@section('backend_content')
    <div class="container px-6 mx-auto grid">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">Edit Variation</h2>

        <div class="flex items-center mb-6">
            <a href="{{ route('backend.products.edit', $product->id) }}" class="text-purple-600 hover:underline">
                &larr; Back to Product
            </a>
            <span class="mx-2 text-gray-500">|</span>
            <span class="text-gray-700">Product: {{ $product->name }}</span>
        </div>

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

        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="text-md font-semibold mb-3">Variation Attributes:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($variation->attributeValues as $attrValue)
                        <span class="px-2 py-1 text-sm bg-gray-100 text-gray-800 rounded-full">
                            {{ $attrValue->attribute->name }}: {{ $attrValue->value }}
                        </span>
                    @endforeach
                </div>
            </div>

            <form
                action="{{ route('backend.products.variations.update', ['product' => $product->id, 'variation' => $variation->id]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <span>SKU</span>
                            <input type="text" name="sku" value="{{ old('sku', $variation->sku) }}"
                                class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                placeholder="SKU">
                        </label>
                        @error('sku')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            <span>Price</span>
                            <input type="number" name="price" value="{{ old('price', $variation->price) }}"
                                step="0.01" min="0"
                                class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                placeholder="0.00" required>
                        </label>
                        @error('price')
                            <span class="text-xs text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Stock Quantity</span>
                        <input type="number" name="stock_quantity"
                            value="{{ old('stock_quantity', $variation->stock_quantity) }}" min="0"
                            class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                            placeholder="0" required>
                    </label>
                    @error('stock_quantity')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" value="1"
                            {{ $variation->is_default ? 'checked' : '' }}
                            class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Default Variation</span>
                    </label>
                    @error('is_default')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700">
                        <span>Variation Image</span>
                    </label>

                    @if ($variation->image_path)
                        <div class="mb-3">
                            <img src="{{ asset($variation->image_path) }}" alt="{{ $product->name }} variation"
                                class="w-32 h-32 object-cover rounded">
                            <div class="mt-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remove_image" value="1"
                                        class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-400 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-red-600">Remove image</span>
                                </label>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="image" accept="image/*"
                        class="block w-full mt-1 text-sm rounded-md border-gray-300 shadow-sm focus:border-purple-400 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                    <p class="text-xs text-gray-500 mt-1">Upload a new image to replace the current one</p>
                    @error('image')
                        <span class="text-xs text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('backend.products.edit', $product->id) }}"
                        class="px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition-colors duration-150 bg-white border border-gray-300 rounded-md active:bg-gray-50 hover:bg-gray-50 focus:outline-none focus:shadow-outline-gray">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Update Variation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
