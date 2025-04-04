<nav class="text-orange-500 hidden lg:block">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center py-4 px-4">
            <!-- Categories Dropdown -->
            <div class="relative group">
                <button class="flex items-center  justify-center space-x-2 w-[250px] bg-orange-400 text-white px-4 py-2 rounded-md mr-4 hover:text-white hover:bg-orange-600">
                    <i class="ri-menu-2-line"></i>
                    <span>Categories</span>
                    <i class="ri-arrow-down-s-line"></i>
                </button>
                <div class="absolute z-10 hidden group-hover:block w-[250px] bg-white text-gray-700 shadow-lg rounded-bl-md rounded-br-md">
                    <!-- Electronics Category -->
                    <div class="relative group/sub py-2">
                        <div class="flex items-center justify-between px-4 py-2 hover:bg-orange-50 cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <img src="https://placehold.co/32x32" class="w-8 h-8 rounded" alt="Electronics">
                                <h3 class="font-semibold">Electronics</h3>
                            </div>
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                        <!-- Subcategories -->
                        <div class="absolute left-full top-0 hidden group-hover/sub:block w-[200px] bg-white shadow-lg rounded-md">
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Smartphones</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Laptops</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Tablets</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Accessories</a>
                        </div>
                    </div>

                    <!-- Fashion Category -->
                    <div class="relative group/sub py-2">
                        <div class="flex items-center justify-between px-4 py-2 hover:bg-orange-50 cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <img src="https://placehold.co/32x32" class="w-8 h-8 rounded" alt="Fashion">
                                <h3 class="font-semibold">Fashion</h3>
                            </div>
                            <i class="ri-arrow-right-s-line"></i>
                        </div>
                        <!-- Subcategories -->
                        <div class="absolute left-full top-0 hidden group-hover/sub:block w-[200px] bg-white shadow-lg rounded-md">
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Men's Wear</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Women's Wear</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Kids</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Accessories</a>
                        </div>
                    </div>

                    <!-- Add more categories here with the same pattern -->
                </div>
            </div>

            <!-- Main Navigation -->
            <div class="flex items-center space-x-6">
                <a href="#" class=" font-semibold text-gray-900 hover:text-orange-600">Home</a>
                <a href="#" class=" font-semibold text-gray-900 hover:text-orange-600">Home</a>
                
                <!-- Shop Dropdown -->
                <div class="relative group">
                    <button class="flex font-semibold text-gray-900 items-center space-x-2 hover:text-orange-600 py-2">
                        <span>Shop</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="absolute z-10 hidden group-hover:block w-48 bg-white text-gray-700 shadow-lg rounded-md">
                        <!-- Add invisible padding to bridge the gap -->
                        <div class="h-2 -mt-2"></div>
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">New Arrivals</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Best Sellers</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Deals</a>
                        </div>
                    </div>
                </div>

                <!-- Pages Dropdown -->
                <div class="relative group">
                    <button class="flex font-semibold text-gray-900 items-center space-x-2 hover:text-orange-600 py-2">
                        <span>Pages</span>
                        <i class="ri-arrow-down-s-line"></i>
                    </button>
                    <div class="absolute z-10 hidden group-hover:block w-48 bg-white text-gray-700 shadow-lg rounded-md">
                        <div class="h-2 -mt-2"></div>
                        <div class="py-1">
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">About Us</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">Contact</a>
                            <a href="#" class="block px-4 py-2 hover:bg-orange-50">FAQ</a>
                        </div>
                    </div>
                </div>

                <a href="#" class="font-semibold text-gray-900 hover:text-orange-600">Blog</a>
                <a href="#" class="font-semibold text-gray-900 hover:text-orange-600">Contact</a>
            </div>
        </div>
    </div>
</nav>