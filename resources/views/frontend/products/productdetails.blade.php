@extends('frontend.frontend_master')
   @section('frontend_content')
   <!-- Main Content -->
    <!-- product details start  -->
   
        
    <!-- product details start  -->
    <div class="max-w-7xl mx-auto px-4 py-2">
        <!-- Breadcrumb -->
        <div class="flex items-center space-x-2 text-sm mb-8">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-orange-500">Home</a>
            <span class="text-gray-500">/</span>
            @if($product->category)
            <a href="#" class="text-gray-500 hover:text-orange-500">{{ $product->category->name }}</a>
            <span class="text-gray-500">/</span>
            @endif
            <span class="text-orange-500">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Product Images -->
             <div>
            <div class="bg-white p-4 rounded-md shadow-sm">
              
                
                <!-- Main Swiper -->
                <div class="swiper product-swiper rounded-md relative mb-2">
                      <!-- Add discount label -->
                @if ($defaultVariation && $defaultVariation->price < $product->base_price)
                    @php
                        $discountPercentage = round((($product->base_price - $defaultVariation->price) / $product->base_price) * 100);
                    @endphp
                    <div class="absolute top-1 left-1 bg-orange-500 text-white text-xs font-semibold px-2 py-1 rounded-tr-lg rounded-bl-lg z-10">
                        -{{ $discountPercentage }}% OFF
                    </div>
                @endif
                    <div class="swiper-wrapper rounded-md">
                        @if ($primaryImage)
                        <div class="swiper-slide">
                            <img src="{{ asset($primaryImage->image_path) }}" alt="{{ $primaryImage->alt_text ?? $product->name }}" class="w-full h-full object-contain rounded-md">
                        </div>
                        @endif
                        
                        @foreach ($galleryImages as $image)
                        <div class="swiper-slide">
                            <img src="{{ asset($image->image_path) }}" alt="{{ $image->alt_text ?? $product->name }}" class="w-full h-full object-contain rounded-md">
                        </div>
                        @endforeach
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
                        @if ($primaryImage)
                        <div class="swiper-slide rounded-md">
                            <img src="{{ asset($primaryImage->image_path) }}" alt="Thumbnail" class="rounded-md">
                        </div>
                        @endif
                        
                        @foreach ($galleryImages as $image)
                        <div class="swiper-slide rounded-md">
                            <img src="{{ asset($image->image_path) }}" alt="Thumbnail" class="rounded-md">
                        </div>
                        @endforeach
                    </div>
                </div>
               

            </div>
            </div>
            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Title and Reviews -->
                <div class="space-y-2">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>
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
                    <div id="price-container" class="relative">
                        @if ($defaultVariation)
                            <span class="text-3xl font-bold text-orange-500" id="product-price">${{ number_format($defaultVariation->price, 2) }}</span>
                            @if ($defaultVariation->price < $product->base_price)
                                <span class="text-lg text-gray-500 line-through ml-2" id="product-old-price">${{ number_format($product->base_price, 2) }}</span>
                                <span class="bg-orange-100 text-orange-500 px-2 py-1 rounded text-sm ml-2" id="product-discount">{{ $discountPercentage }}% OFF</span>
                            @endif
                        @else
                            <span class="text-3xl font-bold text-orange-500" id="product-price">${{ number_format($product->base_price, 2) }}</span>
                        @endif
                        
                        <!-- Subtle loading overlay for price -->
                        <div id="price-loading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-70 rounded-md z-10 hidden">
                            <div class="flex space-x-1">
                                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse delay-100"></div>
                                <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse delay-200"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Short Description -->
                <div class="space-y-3 py-4 border-t border-b border-gray-200">
                    <p class="text-gray-600 leading-relaxed">
                        {{ Str::limit($product->description, 200) }}
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

                <!-- Dynamic Attribute Selection -->
                @if(count($attributes) > 0)
                    @foreach($attributes as $attrName => $attribute)
                    <div class="space-y-3">
                        <span class="text-gray-600 font-medium">{{ $attrName }}</span>
                        <div class="flex items-center flex-wrap gap-3">
                            @foreach($attribute['values'] as $valueId => $value)
                                <div class="relative">
                                    <input type="radio" name="{{ strtolower($attrName) }}" 
                                        id="{{ strtolower($attrName) }}{{ $valueId }}" 
                                        class="sr-only peer attribute-option" 
                                        value="{{ $valueId }}" 
                                        data-attribute="{{ $attrName }}"
                                        @if($defaultVariation && $defaultVariation->attributeValues->contains($valueId)) checked @endif>
                                    <label for="{{ strtolower($attrName) }}{{ $valueId }}" 
                                        class="flex items-center justify-center px-3 py-2 rounded-lg border-2 border-gray-300 cursor-pointer peer-checked:border-orange-500 peer-checked:bg-orange-50 peer-checked:text-orange-500">
                                        {{ $value }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @endif

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
                                   max="{{ $defaultVariation ? $defaultVariation->stock_quantity : 0 }}" 
                                   class="w-10 h-10 text-center bg-white  text-gray-700 text-lg font-medium border-none focus:none" 
                                   readonly>
                            <button onclick="incrementQuantity()" 
                                    class="w-10 h-10 bg-white hover:bg-orange-50 text-orange-500 flex items-center justify-center border-l border-orange-500 transition-colors duration-200">
                                <i class="ri-add-line text-xl"></i>
                            </button>
                        </div>
                        <span class="text-sm text-gray-500">
                            (<span id="stockCount" class="font-medium text-orange-500">{{ $defaultVariation ? $defaultVariation->stock_quantity : 0 }}</span> pieces available)
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-center items-center space-x-4">
                    <a href="#" class="flex items-center justify-center space-x-2 bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg transition duration-200">
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

                <!-- Payment Methods -->
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
                        <span class="text-gray-900">{{ $defaultVariation ? $defaultVariation->sku : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="text-gray-500">Category:</span>
                        @if($product->category)
                        <a href="#" class="text-orange-500 hover:underline">{{ $product->category->name }}</a>
                        @endif
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
                {{ $product->description }}
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
    
    <!-- Custom script to set the max stock quantity for the product -->
    <script>
        // Debounce function to limit how often a function can run
        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize variables
            const stockQuantity = {{ $defaultVariation ? $defaultVariation->stock_quantity : 0 }};
            const productId = {{ $product->id }};
            let selectedAttributes = {};
            let selectedVariationId = {{ $defaultVariation ? $defaultVariation->id : 'null' }};
            let currentRequest = null; // To track current AJAX request
            
            // Set maximum stock quantity
            document.querySelector('#quantity').setAttribute('max', stockQuantity);
            updateQuantityUI(1);

            // Add event listeners to attribute options with debouncing
            document.querySelectorAll('.attribute-option').forEach(option => {
                option.addEventListener('change', function() {
                    const attribute = this.getAttribute('data-attribute');
                    const valueId = this.value;
                    selectedAttributes[attribute] = valueId;
                    
                    // Use debounced version to avoid multiple rapid requests
                    debouncedUpdateProductVariation();
                });
            });

            // Create debounced version of updateProductVariation
            const debouncedUpdateProductVariation = debounce(updateProductVariation, 300);

            // Setup default selected attributes based on default variation
            @if($defaultVariation)
                document.querySelectorAll('.attribute-option:checked').forEach(option => {
                    const attribute = option.getAttribute('data-attribute');
                    const valueId = option.value;
                    selectedAttributes[attribute] = valueId;
                });
            @endif
            
            // Update the updateQuantityUI function to use the actual stock count
            function updateQuantityUI(value) {
                const input = document.getElementById('quantity');
                const stockCount = document.getElementById('stockCount');
                const maxStock = parseInt(input.getAttribute('max'));
                
                input.value = value;
                stockCount.textContent = maxStock - value;
                
                // Update button states visually
                const decrementBtn = input.previousElementSibling;
                const incrementBtn = input.nextElementSibling;
                
                decrementBtn.classList.toggle('opacity-50', value <= 1);
                decrementBtn.classList.toggle('cursor-not-allowed', value <= 1);
                incrementBtn.classList.toggle('opacity-50', value >= maxStock);
                incrementBtn.classList.toggle('cursor-not-allowed', value >= maxStock);
            }

            // Function to handle product variation changes
            function updateProductVariation() {
                // Get all selected attribute values
                const attributeValueIds = [];
                
                Object.values(selectedAttributes).forEach(valueId => {
                    attributeValueIds.push(parseInt(valueId));
                });
                
                // Only proceed if we have selected values for each attribute
                const attributeCount = {{ count($attributes) }};
                if (Object.keys(selectedAttributes).length !== attributeCount) {
                    return;
                }
                
                // Show subtle loading indicator
                const priceElement = document.querySelector('#product-price');
                const priceContainer = document.querySelector('#price-container');
                
                // Add subtle opacity transition instead of hiding completely
                priceContainer.style.opacity = '0.7';
                
                // Cancel previous request if it exists
                if (currentRequest) {
                    currentRequest.abort();
                }
                
                // Make AJAX request to get variation data
                currentRequest = $.ajax({
                    url: "{{ route('product.variation') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: productId,
                        attribute_values: attributeValueIds
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Use requestAnimationFrame for smoother UI updates
                            requestAnimationFrame(() => {
                                updateProductUI(response);
                                selectedVariationId = response.variation_id;
                                
                                // Restore opacity with transition
                                setTimeout(() => {
                                    priceContainer.style.opacity = '1';
                                }, 50);
                            });
                        } else {
                            // Restore opacity on error
                            priceContainer.style.opacity = '1';
                            console.error('No matching variation found:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        if (status !== 'abort') {
                            console.error("Error fetching variation data:", error);
                            priceContainer.style.opacity = '1';
                        }
                    },
                    complete: function() {
                        currentRequest = null;
                    }
                });
            }
            
            // Function to update product UI with variation data
            function updateProductUI(data) {
                // Update price with smooth transition
                const priceElement = document.querySelector('#product-price');
                priceElement.innerHTML = '$' + parseFloat(data.price).toFixed(2);
                
                // Update original price and discount if applicable
                const oldPriceElement = document.querySelector('#product-old-price');
                const discountElement = document.querySelector('#product-discount');
                
                if (data.discount_percentage) {
                    // Show discount with transition
                    if (oldPriceElement) {
                        oldPriceElement.innerHTML = '$' + parseFloat(data.base_price).toFixed(2);
                        oldPriceElement.style.display = '';
                        oldPriceElement.style.opacity = '0';
                        requestAnimationFrame(() => {
                            oldPriceElement.style.transition = 'opacity 200ms ease';
                            oldPriceElement.style.opacity = '1';
                        });
                    } else {
                        // Create discount elements if they don't exist
                        const priceContainer = priceElement.parentElement;
                        
                        // Only create if they don't already exist
                        if (!document.querySelector('#product-old-price')) {
                            const newOldPrice = document.createElement('span');
                            newOldPrice.id = 'product-old-price';
                            newOldPrice.className = 'text-lg text-gray-500 line-through ml-2';
                            newOldPrice.innerHTML = '$' + parseFloat(data.base_price).toFixed(2);
                            newOldPrice.style.opacity = '0';
                            priceContainer.appendChild(newOldPrice);
                            
                            const newDiscount = document.createElement('span');
                            newDiscount.id = 'product-discount';
                            newDiscount.className = 'bg-orange-100 text-orange-500 px-2 py-1 rounded text-sm ml-2';
                            newDiscount.innerHTML = data.discount_percentage + '% OFF';
                            newDiscount.style.opacity = '0';
                            priceContainer.appendChild(newDiscount);
                            
                            // Trigger reflow and apply transition
                            requestAnimationFrame(() => {
                                newOldPrice.style.transition = 'opacity 200ms ease';
                                newOldPrice.style.opacity = '1';
                                newDiscount.style.transition = 'opacity 200ms ease';
                                newDiscount.style.opacity = '1';
                            });
                        }
                    }
                    
                    if (discountElement) {
                        discountElement.innerHTML = data.discount_percentage + '% OFF';
                        discountElement.style.display = '';
                    }
                    
                    // Update discount badge if it exists
                    const discountBadge = document.querySelector('.absolute.top-1.left-1.bg-orange-500');
                    if (discountBadge) {
                        discountBadge.innerHTML = '-' + data.discount_percentage + '% OFF';
                        discountBadge.style.display = '';
                    }
                } else {
                    // Hide discount elements if there's no discount
                    if (oldPriceElement) {
                        oldPriceElement.style.transition = 'opacity 200ms ease';
                        oldPriceElement.style.opacity = '0';
                        setTimeout(() => {
                            oldPriceElement.style.display = 'none';
                        }, 200);
                    }
                    
                    if (discountElement) {
                        discountElement.style.transition = 'opacity 200ms ease';
                        discountElement.style.opacity = '0';
                        setTimeout(() => {
                            discountElement.style.display = 'none';
                        }, 200);
                    }
                    
                    // Hide discount badge if it exists
                    const discountBadge = document.querySelector('.absolute.top-1.left-1.bg-orange-500');
                    if (discountBadge) {
                        discountBadge.style.transition = 'opacity 200ms ease';
                        discountBadge.style.opacity = '0';
                        setTimeout(() => {
                            discountBadge.style.display = 'none';
                        }, 200);
                    }
                }
                
                // Update stock quantity with smooth transition
                const quantityInput = document.getElementById('quantity');
                const oldMax = parseInt(quantityInput.getAttribute('max'));
                const newMax = data.stock_quantity;
                
                if (oldMax !== newMax) {
                    // Animate the stock count change
                    animateStockCount(oldMax, newMax);
                    quantityInput.setAttribute('max', newMax);
                }
                
                // Ensure quantity is not more than stock
                const currentQty = parseInt(quantityInput.value);
                if (currentQty > newMax) {
                    updateQuantityUI(newMax > 0 ? newMax : 1);
                } else {
                    // Just update the available stock display
                    const stockCount = document.getElementById('stockCount');
                    stockCount.textContent = newMax - currentQty;
                }
                
                // Update SKU with subtle animation
                const skuElement = document.querySelector('.text-gray-900');
                if (skuElement && data.sku) {
                    skuElement.style.transition = 'opacity 200ms ease';
                    skuElement.style.opacity = '0';
                    setTimeout(() => {
                        skuElement.textContent = data.sku;
                        skuElement.style.opacity = '1';
                    }, 200);
                }
                
                // Update product image if variation has one
                if (data.variation_images && data.variation_images.length > 0) {
                    // Get main swiper
                    const mainSwiperSlides = document.querySelector('.product-swiper .swiper-wrapper');
                    if (mainSwiperSlides && mainSwiperSlides.firstElementChild) {
                        const mainImage = mainSwiperSlides.firstElementChild.querySelector('img');
                        if (mainImage) {
                            // Store original src in data attribute if not already stored
                            if (!mainImage.hasAttribute('data-original-src')) {
                                mainImage.setAttribute('data-original-src', mainImage.src);
                            }
                            
                            // Apply smooth image transition
                            mainImage.style.transition = 'opacity 200ms ease';
                            mainImage.style.opacity = '0';
                            setTimeout(() => {
                                mainImage.src = data.variation_images[0].url;
                                mainImage.style.opacity = '1';
                            }, 200);
                        }
                    }
                } else {
                    // Restore original image if available
                    const mainImage = document.querySelector('.product-swiper .swiper-wrapper img');
                    if (mainImage && mainImage.hasAttribute('data-original-src')) {
                        mainImage.style.transition = 'opacity 200ms ease';
                        mainImage.style.opacity = '0';
                        setTimeout(() => {
                            mainImage.src = mainImage.getAttribute('data-original-src');
                            mainImage.style.opacity = '1';
                        }, 200);
                    }
                }
            }
            
            // Animate stock count for smoother transitions
            function animateStockCount(oldValue, newValue) {
                const stockCount = document.getElementById('stockCount');
                const duration = 300; // ms
                const frameRate = 30;
                const totalFrames = duration / (1000 / frameRate);
                const increment = (newValue - oldValue) / totalFrames;
                let currentFrame = 0;
                let currentValue = oldValue;
                
                const animation = setInterval(() => {
                    currentFrame++;
                    currentValue += increment;
                    
                    if (currentFrame === totalFrames) {
                        currentValue = newValue;
                        clearInterval(animation);
                    }
                    
                    stockCount.textContent = Math.round(currentValue);
                }, 1000 / frameRate);
            }

            // Add to cart functionality
            document.querySelectorAll('.flex.items-center.justify-center.w-12.h-12.bg-orange-500').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (selectedVariationId) {
                        // Here you would implement the actual add to cart logic
                        alert('Product added to cart! (Variation ID: ' + selectedVariationId + ')');
                    } else {
                        alert('Please select all product options before adding to cart.');
                    }
                });
            });
        });

        // These functions are defined outside to make them globally accessible for button onclick
        function updateQuantityUI(value) {
            const input = document.getElementById('quantity');
            const stockCount = document.getElementById('stockCount');
            const maxStock = parseInt(input.getAttribute('max'));
            
            input.value = value;
            stockCount.textContent = maxStock - value;
            
            // Update button states visually
            const decrementBtn = input.previousElementSibling;
            const incrementBtn = input.nextElementSibling;
            
            decrementBtn.classList.toggle('opacity-50', value <= 1);
            decrementBtn.classList.toggle('cursor-not-allowed', value <= 1);
            incrementBtn.classList.toggle('opacity-50', value >= maxStock);
            incrementBtn.classList.toggle('cursor-not-allowed', value >= maxStock);
        }
    </script>

    <!-- Include jQuery for easier AJAX handling -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection