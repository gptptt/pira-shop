<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $products = Product::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->when($request->filled('sort') && $request->filled('direction'), function ($query) use ($request) {
                $query->orderBy($request->sort, $request->direction);
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->when($request->boolean('with_pricing_plans'), function ($query) {
                $query->with('pricingPlans');
            })
            ->when(!$request->boolean('include_inactive') && !$request->filled('status'), function ($query) {
                $query->where('is_active', true);
            })
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }
        
        // Convert features from string to array if needed
        if (isset($validated['features']) && is_string($validated['features'])) {
            $validated['features'] = array_map('trim', explode("\n", $validated['features']));
        }
        
        $product = Product::create($validated);
        
        return (new ProductResource($product))
            ->additional(['message' => 'Product created successfully'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified product.
     *
     * @param Product $product
     * @param Request $request
     * @return ProductResource
     */
    public function show(Product $product, Request $request): ProductResource
    {
        if ($request->boolean('with_pricing_plans')) {
            $product->load('pricingPlans');
        }
        
        return new ProductResource($product);
    }

    /**
     * Update the specified product in storage.
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @return ProductResource
     */
    public function update(UpdateProductRequest $request, Product $product): ProductResource
    {
        $validated = $request->validated();
        
        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }
        
        // Convert features from string to array if needed
        if (isset($validated['features']) && is_string($validated['features'])) {
            $validated['features'] = array_map('trim', explode("\n", $validated['features']));
        }
        
        $product->update($validated);
        
        return (new ProductResource($product))
            ->additional(['message' => 'Product updated successfully']);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        // Delete the product image if it exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();
        
        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
    
    /**
     * Toggle the status of the specified product.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function toggleStatus(Product $product): ProductResource
    {
        $this->authorize('update', $product);
        
        $product->update([
            'is_active' => !$product->is_active,
        ]);
        
        return (new ProductResource($product))
            ->additional(['message' => 'Product status updated successfully']);
    }
    
    /**
     * Get all active products for public display.
     *
     * @return ResourceCollection
     */
    public function getActiveProducts(): ResourceCollection
    {
        $products = Product::where('is_active', true)
            ->with(['pricingPlans' => function ($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
            
        return ProductResource::collection($products);
    }
}
