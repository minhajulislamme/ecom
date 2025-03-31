<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Backend\Category\CategoryController;
use App\Http\Controllers\Backend\Category\SubcategoryController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

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
        // Route::get('/get-subcategories/{category_id}', 'getSubcategories')->name('get.subcategories');
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
