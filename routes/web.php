<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Backend\Category\CategoryController;
use App\Http\Controllers\Backend\Category\SubcategoryController;
use App\Http\Controllers\Backend\Product\BackendProductController;
use App\Http\Controllers\Backend\Product\ProductImageController;
use App\Http\Controllers\Backend\Product\ProductVariationImageController;
use App\Http\Controllers\Frontend\Home\HomeController;
use App\Http\Controllers\Frontend\Home\ProductDetailsController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', function () {
//     return view('frontend.index');
// });
// Route::get('/product/details', function () {
//     return view('frontend.products.productdetails');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


// For frontend routes

Route::get('/', [HomeController::class, 'index'])->name('home');

//product details route
Route::get('/product-details/{id}/{slug}', [ProductDetailsController::class, 'ProductDetails'])->name('product.details');
Route::post('/product-variation', [ProductDetailsController::class, 'getProductVariation'])->name('product.variation');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// User Routes
Route::middleware(['auth', 'verified', 'user'])->group(function () {
    Route::get('/dashboard', function () {
        return view('frontend.user');
    })->name('user.dashboard');
});

// Admin All Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'AdminProfileUpdate'])->name('admin.profile.update');
    Route::get('/admin/password', [AdminController::class, 'AdminPassword'])->name('admin.password');
    Route::post('/admin/password/update', [AdminController::class, 'AdminPasswordUpdate'])->name('admin.password.update');

    // All Category Routes
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'AllCategory')->name('all.category');
        Route::get('/category/add', 'CategoryAdd')->name('category.add');
        Route::post('/category/store', 'CategoryStore')->name('category.store');
        Route::get('/category/edit/{id}', 'CategoryEdit')->name('category.edit');
        Route::post('/category/update', 'CategoryUpdate')->name('category.update');
        Route::get('/category/delete/{id}', 'CategoryDelete')->name('category.delete');
    });

    // All SubCategory Routes
    Route::controller(SubcategoryController::class)->group(function () {
        Route::get('/subcategory', 'AllSubCategory')->name('all.subcategory');
        Route::get('/subcategory/add', 'SubCategoryAdd')->name('subcategory.add');
        Route::post('/subcategory/store', 'SubCategoryStore')->name('subcategory.store');
        Route::get('/subcategory/edit/{id}', 'SubCategoryEdit')->name('subcategory.edit');
        Route::post('/subcategory/update/{id}', 'SubCategoryUpdate')->name('subcategory.update');
        Route::get('/subcategory/delete/{id}', 'SubCategoryDelete')->name('subcategory.delete');
        Route::get('/get-subcategories/{category_id}', 'getSubcategories')->name('get.subcategories');
    });

    // All Product Routes
    Route::controller(BackendProductController::class)->group(function () {
        Route::get('/product', 'index')->name('backend.products.index');
        Route::get('/product/add', 'create')->name('backend.products.create');
        Route::post('/product/store', 'store')->name('backend.products.store');
        Route::get('/product/edit/{product}', 'edit')->name('backend.products.edit');
        Route::put('/product/update/{product}', 'update')->name('backend.products.update');
        Route::delete('/product/delete/{product}', 'destroy')->name('backend.products.destroy');

        // Product Attributes
        Route::post('/product/{product}/attributes', 'addAttribute')->name('backend.products.attributes.add');
        Route::delete('/product/{product}/attributes/{attribute}', 'removeAttribute')->name('backend.products.attributes.remove');

        // Product Variations
        Route::get('/product/{product}/variations/create', 'createVariations')->name('backend.products.variations.create');
        Route::post('/product/{product}/variations', 'storeVariations')->name('backend.products.variations.store');
        Route::get('/product/{product}/variations/{variation}/edit', 'editVariation')->name('backend.products.variations.edit');
        Route::put('/product/{product}/variations/{variation}', 'updateVariation')->name('backend.products.variations.update');
        Route::delete('/product/{product}/variations/{variation}', 'removeVariation')->name('backend.products.variations.destroy');
    });

    // Product Images
    Route::controller(ProductImageController::class)->group(function () {
        Route::post('/product/{product}/images', 'store')->name('product.images.store');
        Route::put('/product-images/{productImage}', 'update')->name('product.images.update');
        Route::delete('/product-images/{productImage}', 'destroy')->name('product.images.destroy');
    });

    // Variation Images
    Route::controller(ProductVariationImageController::class)->group(function () {
        Route::post('/variations/{variation}/images', 'store')->name('variations.images.store');
        Route::put('/variation-images/{variationImage}', 'update')->name('variations.images.update');
        Route::delete('/variation-images/{variationImage}', 'destroy')->name('variations.images.destroy');
    });

    //end
});
// Super Admin Routes
Route::middleware(['auth', 'verified', 'super_admin'])->group(function () {
    Route::get('/superadmin/dashboard', function () {
        return view('superadmin.superadmin');
    })->name('superadmin.dashboard');
});


// Admin Login Routes
Route::get('/admin/login', [AdminController::class, 'AdminLogin'])->name('admin.login');
Route::post('/admin/login/store', [AdminController::class, 'AdminLoginStore'])->name('admin.login.store');
