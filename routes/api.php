<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ExampleResourceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PricingPlanController;
use App\Http\Controllers\Api\ProductFeatureController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
 * Public Product Routes
 */
Route::get('products/active', [App\Http\Controllers\Api\ProductController::class, 'getActiveProducts']);
Route::get('products/{product:slug}', [App\Http\Controllers\Api\ProductController::class, 'show']);

/*
 * Public Pricing Plan Routes
 */
Route::get('pricing-plans/comparison', [PricingPlanController::class, 'getPricingComparison']);
Route::get('products/{product}/pricing-plans', [PricingPlanController::class, 'getProductPlans']);
Route::get('pricing-plans/{pricing_plan:slug}', [PricingPlanController::class, 'show']);

/*
 * Public Product Feature Routes
 */
Route::get('products/{product}/features', [App\Http\Controllers\Api\ProductFeatureController::class, 'getProductFeatures']);
Route::get('product-features/{product_feature:key}', [App\Http\Controllers\Api\ProductFeatureController::class, 'show']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Example resource routes
    Route::apiResource('examples', ExampleResourceController::class);
    
    // Routes that require specific roles or permissions
    Route::middleware('role:admin')->group(function () {
        // Admin only routes
    });
    
    Route::middleware('role:admin,sales')->group(function () {
        // Admin and sales routes
    });
    
    Route::middleware('permission:manage-users')->group(function () {
        // Routes for users with manage-users permission
    });

    // Protected Product Routes
    Route::apiResource('products', App\Http\Controllers\Api\ProductController::class)->except('show');
    Route::patch('products/{product}/toggle-status', [App\Http\Controllers\Api\ProductController::class, 'toggleStatus']);
    
    // Protected Pricing Plan Routes
    Route::apiResource('pricing-plans', PricingPlanController::class)->except('show');
    Route::patch('pricing-plans/{pricing_plan}/toggle-status', [PricingPlanController::class, 'toggleStatus']);
    Route::patch('pricing-plans/{pricing_plan}/toggle-featured', [PricingPlanController::class, 'toggleFeatured']);
    Route::post('pricing-plans/create-multiple-tiers', [PricingPlanController::class, 'createMultipleTiers']);
    Route::post('pricing-plans/create-enterprise-tier', [PricingPlanController::class, 'createEnterpriseTier']);
    
    // Protected Product Feature Routes
    Route::apiResource('product-features', App\Http\Controllers\Api\ProductFeatureController::class)->except('show');
    Route::patch('product-features/{product_feature}/toggle-status', [App\Http\Controllers\Api\ProductFeatureController::class, 'toggleStatus']);
    Route::patch('product-features/{product_feature}/toggle-highlighted', [App\Http\Controllers\Api\ProductFeatureController::class, 'toggleHighlighted']);
    Route::patch('product-features/{product_feature}/toggle-public', [App\Http\Controllers\Api\ProductFeatureController::class, 'togglePublic']);
    Route::post('product-features/batch-update', [App\Http\Controllers\Api\ProductFeatureController::class, 'batchUpdate']);
});
