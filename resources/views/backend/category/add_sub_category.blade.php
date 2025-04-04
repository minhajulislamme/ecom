@extends('backend.dashboard')
@section('backend_content')
    <div class="p-6">
        <div class="p-6 bg-white items-center shadow-md shadow-black/5 rounded-md border border-gray-100 mb-6">
            <div class="flex justify-between mb-6">
                <div>
                    <div class="text-lg font-semibold">Add New Subcategory</div>
                    <div class="text-sm font-medium text-gray-400">Add new subcategory to your store</div>
                </div>
                <div class="dropdown">
                    <button type="button"
                        class="dropdown-toggle text-gray-400 w-8 h-8 rounded flex items-center justify-center hover:bg-gray-50 hover:text-gray-600">
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <div
                        class="dropdown-menu hidden shadow-md shadow-black/5 z-30 w-full max-w-[140px] bg-white rounded-md border border-gray-100">
                        <ul>


                            <li>
                                <a href="{{ route('all.subcategory') }}"
                                    class="py-2 px-4 text-[13px] flex items-center hover:bg-gray-50 group">
                                    <i class="ri-file-list-line text-gray-400 mr-3"></i>
                                    <span class="text-gray-600 group-hover:text-orange-500 font-medium">All SubCategory</span>
                                </a>
                            </li>


                        </ul>
                    </div>

                </div>

            </div>

            <div>
                <form action="{{ route('subcategory.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category Select -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                            <select name="category_id"
                                class="w-full px-4 py-2 border @error('category_id') border-red-500 @enderror border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subcategory Name Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subcategory Name</label>
                            <input type="text" name="subcategory_name" value="{{ old('subcategory_name') }}"
                                placeholder="Enter subcategory name"
                                class="w-full px-4 py-2 border @error('subcategory_name') border-red-500 @enderror border-gray-300 rounded-md focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none transition">
                            @error('subcategory_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-start pt-4">
                        <button type="submit"
                            class="px-6 py-2.5 bg-orange-600 text-white font-medium text-sm rounded-lg hover:bg-orange-700 focus:ring-4 focus:ring-orange-300 transition duration-200">
                            Add Subcategory
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
