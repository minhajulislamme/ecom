<div id="mobileMenu" class="fixed inset-0 bg-black/50 bg-opacity-50 z-50 hidden lg:hidden">
    <div id="menuContent" class="fixed top-0 left-0 bottom-0 w-80 md:w-96 bg-white transform -translate-x-full transition-transform duration-300 ease-in-out">
        <!-- Menu Header -->
        <div class="flex items-center justify-between p-4 md:p-6 border-b border-orange-300">
            <div class="flex items-center space-x-3">
                <img src="https://placehold.co/32x32" class="w-8 h-8 md:w-10 md:h-10 rounded-md" alt="">
                <span class="text-xl md:text-2xl font-semibold text-orange-400">Shop Ever</span>
            </div>
            <a href="#" class="text-gray-500 hover:text-orange-400" onclick="toggleMenu()">
                <i class="ri-close-line text-2xl md:text-3xl"></i>
            </a>
        </div>
        
        <!-- Menu Items -->
        <div class="py-4 md:py-6">
            <a href="#" class="block px-4 md:px-6 py-2 md:py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 cursor-pointer text-base md:text-lg">Home</a>
            <a href="#" class="block px-4 md:px-6 py-2 md:py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 cursor-pointer text-base md:text-lg">New Arrivals</a>
            <a href="#" class="block px-4 md:px-6 py-2 md:py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 cursor-pointer text-base md:text-lg">Best Sellers</a>
            <a href="#" class="block px-4 md:px-6 py-2 md:py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 cursor-pointer text-base md:text-lg">Deals</a>
            <a href="#" class="block px-4 md:px-6 py-2 md:py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 cursor-pointer text-base md:text-lg">Blog</a>
            <a href="#" class="block px-4 md:px-6 py-2 md:py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 cursor-pointer text-base md:text-lg">Contact</a>
        </div>

        <!-- Menu Footer -->
        <div class="absolute bottom-0 left-0 right-0 border-t p-4 md:p-6">
            <div class="flex items-center space-x-3 text-base md:text-lg">
                <i class="ri-user-line text-orange-400 text-xl md:text-2xl"></i>
                <span>Login / Register</span>
            </div>
        </div>
    </div>
</div>