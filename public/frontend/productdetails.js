document.addEventListener('DOMContentLoaded', function() {
    // Initialize thumbnail swiper
    const thumbsSwiper = new Swiper('.product-thumbs', {
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });

    // Initialize main swiper
    const mainSwiper = new Swiper('.product-swiper', {
        spaceBetween: 10,
        slidesPerView: 1,
        effect: "slide",
        grabCursor: true,
        thumbs: {
            swiper: thumbsSwiper
        },
    });

    // Initialize tabs
    switchTab('description');
});

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

function incrementQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    const maxValue = parseInt(input.getAttribute('max'));
    
    if (currentValue < maxValue) {
        updateQuantityUI(currentValue + 1);
    }
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    
    if (currentValue > 1) {
        updateQuantityUI(currentValue - 1);
    }
}

document.getElementById('quantity').addEventListener('keydown', (e) => {
    e.preventDefault();
});

function switchTab(tabId) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Reset all tab buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-orange-500');
        btn.querySelector('span').style.transform = 'scaleX(0)';
    });
    
    // Show the selected tab
    document.getElementById(tabId).classList.remove('hidden');
    
    // Activate the tab button
    document.getElementById(tabId + 'Tab').classList.add('text-orange-500');
    document.getElementById(tabId + 'Tab').querySelector('span').style.transform = 'scaleX(1)';
}

function setRating(rating) {
    // Update the stars visually
    document.querySelectorAll('.rating-star').forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-400');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-400');
        }
    });
    
    // You could add a hidden input to store the rating
    if (!document.getElementById('review-rating')) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.id = 'review-rating';
        input.name = 'rating';
        input.value = rating;
        document.querySelector('form').appendChild(input);
    } else {
        document.getElementById('review-rating').value = rating;
    }
}

function openVideoModal() {
    const modal = document.getElementById('videoModal');
    modal.classList.remove('hidden');
}

function closeVideoModal() {
    const modal = document.getElementById('videoModal');
    const iframe = document.getElementById('youtubeVideo');
    // Reset iframe by reloading it
    iframe.src = iframe.src;
    modal.classList.add('hidden');
}

// Close modal on background click
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('videoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeVideoModal();
        }
    });
});