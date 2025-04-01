   
   @extends('frontend.frontend_master')
   @section('frontend_content')
   <!-- Main Content -->
    <!-- produt details start  -->
   
        
    <!-- produt details start  -->
    <div class="max-w-7xl mx-auto px-4 py-2">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm mb-8">
            <a href="#" class="text-gray-500 hover:text-orange-500">Home</a>
            <span class="text-gray-500">/</span>
            <span class="text-orange-500">Product Details</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
             <div>
            <div class=" bg-white p-4 rounded-md shadow-sm ">
              
                
                <!-- Main Swiper -->
                <div class="swiper product-swiper rounded-md relative mb-2">
                      <!-- Add discount label -->
                <div class="absolute top-1 left-1 bg-orange-500 text-white text-xs font-semibold px-2 py-1 rounded-tr-lg rounded-bl-lg z-10">
                    -25% OFF
                </div>
                    <div class="swiper-wrapper rounded-md">
                        <div class="swiper-slide">
                            <img src="https://placehold.co/600x600" alt="Product 1" class="w-full h-full object-contain rounded-md">
                        </div>
                        <div class="swiper-slide">
                            <img src="https://placehold.co/600x600" alt="Product 2" class="w-full h-full object-contain rounded-md">
                        </div>
                        <div class="swiper-slide">
                            <img src="https://placehold.co/600x600" alt="Product 3" class="w-full h-full object-contain rounded-md">
                        </div>
                        <div class="swiper-slide">
                            <img src="https://placehold.co/600x600" alt="Product 4" class="w-full h-full object-contain rounded-md">
                        </div>
                        <div class="swiper-slide">
                            <img src="https://placehold.co/600x600" alt="Product 5" class="w-full h-full object-contain rounded-md">
                        </div>
                    </div>
                    <div class="relative">
                        <!-- Video Preview Button -->
                        <button onclick="openVideoModal()" class="absolute bottom-2 left-2 flex items-center space-x-2 bg-white/90 hover:bg-white text-gray-800 px-2 py-2 rounded-full shadow-lg transition-all duration-300 group z-10">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                <i class="ri-play-fill text-white text-xl"></i>
                            </div>
                           
                        </button>
                    </div>
                </div>
                
                <!-- Thumbnails -->
                <div class="swiper product-thumbs">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide  rounded-md">
                            <img src="https://placehold.co/600x600" alt="Thumbnail 1" class="rounded-md">
                        </div>
                        <div class="swiper-slide  rounded-md">
                            <img src="https://placehold.co/600x600" alt="Thumbnail 2" class="rounded-md">
                        </div>
                        <div class="swiper-slide  rounded-md">
                            <img src="https://placehold.co/600x600" alt="Thumbnail 3" class="rounded-md">
                        </div>
                        <div class="swiper-slide  rounded-md">
                            <img src="https://placehold.co/600x600" alt="Thumbnail 4" class="rounded-md">
                        </div>
                        <div class="swiper-slide  rounded-md">
                            <img src="https://placehold.co/600x600" alt="Thumbnail 5" class="rounded-md">
                        </div>
                        
                    </div>
                </div>
               

            </div>
            </div>
            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Title and Reviews -->
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold text-gray-800">Premium Quality Product Name</h1>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <i class="ri-star-fill text-yellow-400"></i>
                            <i class="ri-star-fill text-yellow-400"></i>
                            <i class="ri-star-fill text-yellow-400"></i>
                            <i class="ri-star-fill text-yellow-400"></i>
                            <i class="ri-star-half-fill text-yellow-400"></i>
                        </div>
                        <span class="text-sm text-gray-500">(150 Reviews)</span>
                    </div>
                </div>

                <!-- Price -->
                <div class="flex items-center space-x-4">
                    <span class="text-3xl font-bold text-orange-500">$299.99</span>
                    <span class="text-lg text-gray-500 line-through">$399.99</span>
                    <span class="bg-orange-100 text-orange-500 px-2 py-1 rounded text-sm">25% OFF</span>
                </div>

                <!-- Product Short Description -->
                <div class="space-y-3 py-4 border-t border-b border-gray-200">
                    <p class="text-gray-600 leading-relaxed">
                        Premium quality product with elegant design and superior craftsmanship. Made from high-grade materials 
                        ensuring durability and long-lasting performance. Perfect for everyday use with its versatile features 
                        and modern aesthetics.
                    </p>
                    <ul class="space-y-1 text-gray-600">
                        <li class="flex items-center space-x-2">
                            <i class="ri-checkbox-circle-line text-orange-500"></i>
                            <span>Premium Quality Materials</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="ri-checkbox-circle-line text-orange-500"></i>
                            <span>Durable & Long-lasting</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="ri-checkbox-circle-line text-orange-500"></i>
                            <span>30-Day Money Back Guarantee</span>
                        </li>
                    </ul>
                </div>

                <!-- Color Selection -->
                <div class="space-y-3">
                    <span class="text-gray-600 font-medium">Color</span>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="radio" name="color" id="red" class="sr-only peer">
                            <label for="red" class="block w-8 h-8 rounded-full bg-red-500 cursor-pointer ring-offset-2 peer-checked:ring-2 peer-checked:ring-red-500"></label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="color" id="blue" class="sr-only peer">
                            <label for="blue" class="block w-8 h-8 rounded-full bg-blue-500 cursor-pointer ring-offset-2 peer-checked:ring-2 peer-checked:ring-blue-500"></label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="color" id="green" class="sr-only peer">
                            <label for="green" class="block w-8 h-8 rounded-full bg-green-500 cursor-pointer ring-offset-2 peer-checked:ring-2 peer-checked:ring-green-500"></label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="color" id="purple" class="sr-only peer">
                            <label for="purple" class="block w-8 h-8 rounded-full bg-purple-500 cursor-pointer ring-offset-2 peer-checked:ring-2 peer-checked:ring-purple-500"></label>
                        </div>
                    </div>
                </div>

                <!-- Size Selection -->
                <div class="space-y-3">
                    <span class="text-gray-600 font-medium">Size</span>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <input type="radio" name="size" id="XS" class="sr-only peer">
                            <label for="XS" class="flex items-center justify-center w-10 h-10 rounded-lg border-2 border-gray-300 cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-500">XS</label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="size" id="S" class="sr-only peer">
                            <label for="S" class="flex items-center justify-center w-10 h-10 rounded-lg border-2 border-gray-300 cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-500">S</label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="size" id="M" class="sr-only peer">
                            <label for="M" class="flex items-center justify-center w-10 h-10 rounded-lg border-2 border-gray-300 cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-500">M</label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="size" id="L" class="sr-only peer">
                            <label for="L" class="flex items-center justify-center w-10 h-10 rounded-lg border-2 border-gray-300 cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-500">L</label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="size" id="XL" class="sr-only peer">
                            <label for="XL" class="flex items-center justify-center w-10 h-10 rounded-lg border-2 border-gray-300 cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-500">XL</label>
                        </div>
                    </div>
                </div>

                <!-- Quantity -->
                <div class="space-y-3">
    <span class="text-gray-600 font-medium">Quantity</span>
    <div class="flex items-center space-x-4">
        <div class="flex items-center border border-orange-500 rounded-lg overflow-hidden">
            <button onclick="decrementQuantity()" 
                    class="w-10 h-10 bg-white hover:bg-orange-50 text-orange-500 flex items-center justify-center border-r border-orange-500 transition-colors duration-200">
                <i class="ri-subtract-line text-xl"></i>
            </button>
            <input type="number" 
                   id="quantity" 
                   value="1" 
                   min="1" 
                   max="12" 
                   class="w-10 h-10 text-center bg-white  text-gray-700 text-lg font-medium border-none focus:none" 
                   readonly>
            <button onclick="incrementQuantity()" 
                    class="w-10 h-10 bg-white hover:bg-orange-50 text-orange-500 flex items-center justify-center border-l border-orange-500 transition-colors duration-200">
                <i class="ri-add-line text-xl"></i>
            </button>
        </div>
        <span class="text-sm text-gray-500">
            (<span id="stockCount" class="font-medium text-orange-500">12</span> pieces available)
        </span>
    </div>
</div>

                <!-- Replace the Action Buttons section with this updated version -->
<div class="flex justify-center items-center space-x-4">
    <a href="#" class=" flex items-center justify-center space-x-2 bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg transition duration-200">
        <i class="ri-shopping-bag-line"></i>
        <span>Buy Now</span>
    </a>
    <div class="flex items-center space-x-2">
        <a href="#" class="flex items-center justify-center w-12 h-12 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition duration-200">
            <i class="ri-shopping-cart-2-line text-xl"></i>
        </a>
        <a href="#" class="flex items-center justify-center w-12 h-12 border-2 border-gray-300 hover:border-orange-500 hover:text-orange-500 rounded-lg transition duration-200">
            <i class="ri-heart-line text-xl"></i>
        </a>
    </div>
</div>

<!-- Add this section right after the action buttons and before the Product Meta -->
<div class="mt-6 border-t pt-6">
    <div class="flex flex-col space-y-3">
        <span class="text-sm font-medium text-gray-600">Accepted Payment Methods</span>
        <div class="flex items-center space-x-4">
            <img src="https://placehold.co/40x25" alt="Visa" class="h-8 object-contain grayscale hover:grayscale-0 transition-all duration-200">
            <img src="https://placehold.co/40x25" alt="Mastercard" class="h-8 object-contain grayscale hover:grayscale-0 transition-all duration-200">
            <img src="https://placehold.co/40x25" alt="American Express" class="h-8 object-contain grayscale hover:grayscale-0 transition-all duration-200">
            <img src="https://placehold.co/40x25" alt="PayPal" class="h-8 object-contain grayscale hover:grayscale-0 transition-all duration-200">
            <img src="https://placehold.co/40x25" alt="Apple Pay" class="h-8 object-contain grayscale hover:grayscale-0 transition-all duration-200">
        </div>
    </div>
</div>


                <!-- Product Meta -->
                <div class="border-t pt-6 space-y-4">
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-gray-500">SKU:</span>
                        <span class="text-gray-900">SKU-12345</span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-gray-500">Category:</span>
                        <a href="#" class="text-orange-500 hover:underline">Fashion</a>
                    </div>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-gray-500">Share:</span>
                        <div class="flex items-center space-x-2">
                            <a href="#" class="hover:text-orange-500"><i class="ri-facebook-fill"></i></a>
                            <a href="#" class="hover:text-orange-500"><i class="ri-twitter-fill"></i></a>
                            <a href="#" class="hover:text-orange-500"><i class="ri-instagram-fill"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- product details end -->

   <!-- product description and reviews start -->
    
   <!-- product description and reviews start -->
    <!-- Add this section after the product details and before the delivery trust section -->
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-8">
        <div class="flex space-x-8">
            <button onclick="switchTab('description')" id="descriptionTab" class="tab-btn pb-4 text-lg font-medium transition-colors duration-200 relative">
                Description
                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-orange-500 transform scale-x-0 transition-transform duration-300"></span>
            </button>
            <button onclick="switchTab('reviews')" id="reviewsTab" class="tab-btn pb-4 text-lg font-medium transition-colors duration-200 relative">
                Reviews (5)
                <span class="absolute bottom-0 left-0 w-full h-0.5 bg-orange-500 transform scale-x-0 transition-transform duration-300"></span>
            </button>
        </div>
    </div>

    <!-- Description Content -->
    <div id="description" class="tab-content">
        <div class="prose max-w-none">
            <h3 class="text-xl font-semibold mb-4">Product Description</h3>
            <p class="text-gray-600 mb-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
            </p>
            <ul class="list-disc pl-5 text-gray-600 space-y-2">
                <li>High-quality materials</li>
                <li>Durable construction</li>
                <li>Premium finishing</li>
                <li>Easy maintenance</li>
            </ul>
        </div>
    </div>

    <!-- Reviews Content -->
    <div id="reviews" class="tab-content hidden">
        <!-- Reviews Summary -->
        <div class="flex flex-col space-y-6">
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <div class="text-5xl font-bold text-gray-900 mb-2">4.8</div>
                    <div class="flex items-center justify-center mb-2">
                        <i class="ri-star-fill text-yellow-400"></i>
                        <i class="ri-star-fill text-yellow-400"></i>
                        <i class="ri-star-fill text-yellow-400"></i>
                        <i class="ri-star-fill text-yellow-400"></i>
                        <i class="ri-star-half-fill text-yellow-400"></i>
                    </div>
                    <div class="text-sm text-gray-500">Based on 125 reviews</div>
                </div>
                <div class="flex-1">
                    <!-- Rating Bars -->
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <span class="w-12 text-sm text-gray-600">5 star</span>
                            <div class="flex-1 h-2 mx-2 bg-gray-200 rounded">
                                <div class="h-full w-4/5 bg-yellow-400 rounded"></div>
                            </div>
                            <span class="w-12 text-sm text-gray-600">80%</span>
                        </div>
                        <!-- Add more rating bars similarly -->
                    </div>
                </div>
            </div>

            <!-- Review Form -->
            <div class="bg-gray-50 p-6  mb-6 rounded-lg">
                <h4 class="text-lg font-semibold mb-4">Write a Review</h4>
                <form class="space-y-4">
                    <!-- Add Name Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                        <input type="text" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500"
                               placeholder="Enter your name">
                    </div>
                    
                    <!-- Rating Stars -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="setRating(1)" class="rating-star text-2xl text-gray-400 hover:text-yellow-400">★</button>
                            <button type="button" onclick="setRating(2)" class="rating-star text-2xl text-gray-400 hover:text-yellow-400">★</button>
                            <button type="button" onclick="setRating(3)" class="rating-star text-2xl text-gray-400 hover:text-yellow-400">★</button>
                            <button type="button" onclick="setRating(4)" class="rating-star text-2xl text-gray-400 hover:text-yellow-400">★</button>
                            <button type="button" onclick="setRating(5)" class="rating-star text-2xl text-gray-400 hover:text-yellow-400">★</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Review</label>
                        <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-orange-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 transition-colors">
                        Submit Review
                    </button>
                </form>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="space-y-6">
            <!-- Review Item -->
            <div class="border-b pb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <img src="https://placehold.co/40x40" alt="User" class="w-10 h-10 rounded-full">
                        <div>
                            <h5 class="font-medium">John Doe</h5>
                            <div class="flex items-center">
                                <i class="ri-star-fill text-yellow-400"></i>
                                <i class="ri-star-fill text-yellow-400"></i>
                                <i class="ri-star-fill text-yellow-400"></i>
                                <i class="ri-star-fill text-yellow-400"></i>
                                <i class="ri-star-fill text-yellow-400"></i>
                            </div>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">2 days ago</span>
                </div>
                <p class="text-gray-600">Great product! Exactly what I was looking for. The quality is excellent and it arrived quickly.</p>
            </div>

            <!-- Add more review items here -->
        </div>
    </div>
</div>

    <!-- main content end  -->

    <!-- show more product  end  -->

             <!-- flash selas start  -->


@include('frontend.home.flashsales')

    <!-- flash selas end  -->




   <!-- product start  -->

<!-- product end  --> 





@include('frontend.home.newarrivelproducts')


    <!-- vieo model start  -->

    <!-- Video Modal -->
<div id="videoModal" class="fixed inset-0 bg-black/75 z-50 hidden">
    <div class="absolute inset-6 md:inset-12 lg:inset-24">
        <div class="relative w-full h-full">
            <!-- Close Button -->
            <button onclick="closeVideoModal()" class="absolute -top-4 -right-4 w-10 h-10 bg-white rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors z-10">
                <i class="ri-close-line text-2xl"></i>
            </button>
            
            <!-- Video Player -->
            <div class="w-full h-full rounded-lg overflow-hidden">
                <iframe 
                    id="youtubeVideo"
                    width="100%" 
                    height="100%" 
                    src="https://www.youtube.com/embed/9nOxjEEy6j0?si=tlfshr3H9VB6QWhQ"
                    title="Product Video"
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
        </div>
    </div>
</div>





<!-- Update the style section -->
<style>
    .product-swiper {
        width: 100%;
        border-radius: 0.5rem;
    }
    .product-thumbs .swiper-slide {
        opacity: 0.6;
        cursor: pointer;
    }
    .product-thumbs .swiper-slide-thumb-active {
        opacity: 1;
        border: 2px solid #f97316;
    }




   
 
    .tab-btn {
        cursor: pointer;
        outline: none;
    }
    
    .tab-btn:hover {
        color: rgb(249 115 22); /* orange-500 */
    }
    
    .tab-btn span {
        transform-origin: left;
    }
    
    .tab-btn.text-orange-500 span {
        transform: scaleX(1) !important;
    }
    </style>
    

    <!-- vieo model end  -->
@endsection