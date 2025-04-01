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
});

function updateQuantityUI(value) {
    const input = document.getElementById('quantity');
    const stockCount = document.getElementById('stockCount');
    const maxStock = 12;
    
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
    if (currentValue < 12) {
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    updateQuantityUI(1);
});

// Prevent manual input
document.getElementById('quantity').addEventListener('keydown', (e) => {
    e.preventDefault();
});

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
document.getElementById('videoModal').addEventListener('click', function(e) {
if (e.target === this) {
    closeVideoModal();
}
});

function switchTab(tabId) {
    // Get all tab buttons and content
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');
    
    // Hide all contents first
    contents.forEach(content => content.classList.add('hidden'));
    
    // Reset all tabs
    tabs.forEach(tab => {
        tab.classList.remove('text-orange-500');
        tab.classList.add('text-gray-500');
        tab.querySelector('span').style.transform = 'scaleX(0)';
    });
    
    // Show selected content
    document.getElementById(tabId).classList.remove('hidden');
    
    // Activate selected tab
    const activeTab = document.getElementById(tabId + 'Tab');
    activeTab.classList.remove('text-gray-500');
    activeTab.classList.add('text-orange-500');
    activeTab.querySelector('span').style.transform = 'scaleX(1)';
}

// Ensure description tab is active on page load
document.addEventListener('DOMContentLoaded', () => {
    switchTab('description');
});