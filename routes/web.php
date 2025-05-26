<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ConfirmPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// User Authentication Routes
Route::middleware('web')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('logout', [LoginController::class, 'showLogoutForm'])->name('logout.show');
    
    // Registration Routes
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
    
    // Password Reset Routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    
    // Email Verification Routes
    Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
    
    // Password Confirmation Routes
    Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Admin Authentication
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login']);
    Route::post('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    
    // Admin Dashboard & Protected Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('dashboard/refresh', [AdminController::class, 'refresh'])->name('admin.dashboard.refresh');
        
        // Dashboard Export Routes
        Route::get('export/csv', [\App\Http\Controllers\Admin\ExportController::class, 'exportCsv'])->name('admin.export.csv');
        Route::get('export/excel', [\App\Http\Controllers\Admin\ExportController::class, 'exportExcel'])->name('admin.export.excel');
        Route::get('export/pdf', [\App\Http\Controllers\Admin\ExportController::class, 'exportPdf'])->name('admin.export.pdf');
        
        // Users Management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
        
        // Roles Management
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->names('admin.roles');
        
        // Product Management Routes
        Route::resource('products', App\Http\Controllers\Admin\ProductController::class)->names('admin.products');
        Route::patch('products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus'])
            ->name('admin.products.toggle-status');
        Route::patch('products/{product}/toggle-featured', [App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])
            ->name('admin.products.toggle-featured');
        Route::patch('products/{product}/toggle-homepage', [App\Http\Controllers\Admin\ProductController::class, 'toggleHomepage'])
            ->name('admin.products.toggle-homepage');
        Route::patch('products/{product}/toggle-highlighted', [App\Http\Controllers\Admin\ProductController::class, 'toggleHighlighted'])
            ->name('admin.products.toggle-highlighted');
        Route::patch('products/{product}/visibility', [App\Http\Controllers\Admin\ProductController::class, 'updateVisibility'])
            ->name('admin.products.update-visibility');
        Route::patch('products/{product}/availability', [App\Http\Controllers\Admin\ProductController::class, 'updateAvailability'])
            ->name('admin.products.update-availability');
        Route::patch('products/{product}/scheduling', [App\Http\Controllers\Admin\ProductController::class, 'updateScheduling'])
            ->name('admin.products.update-scheduling');
        Route::post('products/bulk-action', [App\Http\Controllers\Admin\ProductController::class, 'bulkAction'])
            ->name('admin.products.bulk-action');
        
        // Pricing Plan Management Routes
        Route::resource('pricing-plans', App\Http\Controllers\Admin\PricingPlanController::class)->names('admin.pricing-plans');
        Route::patch('pricing-plans/{pricing_plan}/toggle-status', [App\Http\Controllers\Admin\PricingPlanController::class, 'toggleStatus'])
            ->name('admin.pricing-plans.toggle-status');
        Route::patch('pricing-plans/{pricing_plan}/toggle-featured', [App\Http\Controllers\Admin\PricingPlanController::class, 'toggleFeatured'])
            ->name('admin.pricing-plans.toggle-featured');
        Route::post('pricing-plans/bulk-action', [App\Http\Controllers\Admin\PricingPlanController::class, 'bulkAction'])
            ->name('admin.pricing-plans.bulk-action');
        Route::get('pricing-plans/create-multiple/tiers', [App\Http\Controllers\Admin\PricingPlanController::class, 'createMultipleTiers'])
            ->name('admin.pricing-plans.create-multiple-tiers');
        Route::post('pricing-plans/store-multiple/tiers', [App\Http\Controllers\Admin\PricingPlanController::class, 'storeMultipleTiers'])
            ->name('admin.pricing-plans.store-multiple-tiers');
        Route::get('pricing-plans/create-enterprise/tier', [App\Http\Controllers\Admin\PricingPlanController::class, 'createEnterpriseTier'])
            ->name('admin.pricing-plans.create-enterprise-tier');
        Route::post('pricing-plans/store-enterprise/tier', [App\Http\Controllers\Admin\PricingPlanController::class, 'storeEnterpriseTier'])
            ->name('admin.pricing-plans.store-enterprise-tier');
        
        // Product Feature Management Routes
        Route::resource('product-features', App\Http\Controllers\Admin\ProductFeatureController::class)->names('admin.product-features');
        Route::patch('product-features/{product_feature}/toggle-status', [App\Http\Controllers\Admin\ProductFeatureController::class, 'toggleStatus'])
            ->name('admin.product-features.toggle-status');
        Route::patch('product-features/{product_feature}/toggle-highlighted', [App\Http\Controllers\Admin\ProductFeatureController::class, 'toggleHighlighted'])
            ->name('admin.product-features.toggle-highlighted');
        Route::patch('product-features/{product_feature}/toggle-public', [App\Http\Controllers\Admin\ProductFeatureController::class, 'togglePublic'])
            ->name('admin.product-features.toggle-public');
        Route::post('product-features/reorder', [App\Http\Controllers\Admin\ProductFeatureController::class, 'reorder'])
            ->name('admin.product-features.reorder');
        Route::post('product-features/bulk-action', [App\Http\Controllers\Admin\ProductFeatureController::class, 'bulkAction'])
            ->name('admin.product-features.bulk-action');
    });
});

// Customer Routes (protected by auth middleware)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
});

// Admin Profile Routes
Route::prefix('admin/profile')->name('admin.profile.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('show');
    Route::get('/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
    Route::get('/password', [App\Http\Controllers\Admin\ProfileController::class, 'editPassword'])->name('edit-password');
    Route::put('/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('update-password');
    Route::get('/notifications', [App\Http\Controllers\Admin\ProfileController::class, 'editNotifications'])->name('edit-notifications');
    Route::put('/notifications', [App\Http\Controllers\Admin\ProfileController::class, 'updateNotifications'])->name('update-notifications');
    Route::post('/photo', [App\Http\Controllers\Admin\ProfileController::class, 'uploadPhoto'])->name('upload-photo');
});
