<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductFeatureRequest;
use App\Http\Requests\Admin\UpdateProductFeatureRequest;
use App\Http\Resources\ProductFeatureResource;
use App\Models\Product;
use App\Models\ProductFeature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ProductFeatureController extends Controller
{
    /**
     * Display a listing of the product features.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $this->authorize('viewAny', ProductFeature::class);
        
        $productFeatures = ProductFeature::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('key', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('product_id'), function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->when($request->filled('is_highlighted'), function ($query) use ($request) {
                $query->where('is_highlighted', (bool)$request->is_highlighted);
            })
            ->when($request->filled('is_public'), function ($query) use ($request) {
                $query->where('is_public', (bool)$request->is_public);
            })
            ->when($request->boolean('with_product'), function ($query) {
                $query->with('product');
            })
            ->when(!$request->boolean('include_inactive') && !$request->filled('status'), function ($query) {
                $query->where('is_active', true);
            })
            ->when($request->filled('sort') && $request->filled('direction'), function ($query) use ($request) {
                $query->orderBy($request->sort, $request->direction);
            }, function ($query) {
                $query->orderBy('sort_order', 'asc');
            })
            ->paginate($request->per_page ?? 15)
            ->withQueryString();

        return ProductFeatureResource::collection($productFeatures);
    }

    /**
     * Store a newly created product feature in storage.
     *
     * @param StoreProductFeatureRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductFeatureRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Convert options from string to array if needed
        if (isset($validated['options']) && is_string($validated['options'])) {
            $validated['options'] = array_map('trim', explode("\n", $validated['options']));
        }
        
        // Set default values
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['is_highlighted'] = $validated['is_highlighted'] ?? false;
        $validated['is_public'] = $validated['is_public'] ?? true;
        
        $productFeature = ProductFeature::create($validated);
        
        return (new ProductFeatureResource($productFeature))
            ->additional(['message' => 'Product feature created successfully'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified product feature.
     *
     * @param ProductFeature $productFeature
     * @param Request $request
     * @return ProductFeatureResource
     */
    public function show(ProductFeature $productFeature, Request $request): ProductFeatureResource
    {
        $this->authorize('view', $productFeature);
        
        if ($request->boolean('with_product')) {
            $productFeature->load('product');
        }
        
        return new ProductFeatureResource($productFeature);
    }

    /**
     * Update the specified product feature in storage.
     *
     * @param UpdateProductFeatureRequest $request
     * @param ProductFeature $productFeature
     * @return ProductFeatureResource
     */
    public function update(UpdateProductFeatureRequest $request, ProductFeature $productFeature): ProductFeatureResource
    {
        $validated = $request->validated();
        
        // Convert options from string to array if needed
        if (isset($validated['options']) && is_string($validated['options'])) {
            $validated['options'] = array_map('trim', explode("\n", $validated['options']));
        }
        
        $productFeature->update($validated);
        
        return (new ProductFeatureResource($productFeature))
            ->additional(['message' => 'Product feature updated successfully']);
    }

    /**
     * Remove the specified product feature from storage.
     *
     * @param ProductFeature $productFeature
     * @return JsonResponse
     */
    public function destroy(ProductFeature $productFeature): JsonResponse
    {
        $this->authorize('delete', $productFeature);
        
        $productFeature->delete();
        
        return response()->json([
            'message' => 'Product feature deleted successfully',
        ]);
    }
    
    /**
     * Toggle the status of the specified product feature.
     *
     * @param ProductFeature $productFeature
     * @return ProductFeatureResource
     */
    public function toggleStatus(ProductFeature $productFeature): ProductFeatureResource
    {
        $this->authorize('update', $productFeature);
        
        $productFeature->update([
            'is_active' => !$productFeature->is_active,
        ]);
        
        return (new ProductFeatureResource($productFeature))
            ->additional(['message' => 'Product feature status updated successfully']);
    }
    
    /**
     * Toggle the highlighted status of the specified product feature.
     *
     * @param ProductFeature $productFeature
     * @return ProductFeatureResource
     */
    public function toggleHighlighted(ProductFeature $productFeature): ProductFeatureResource
    {
        $this->authorize('update', $productFeature);
        
        $productFeature->update([
            'is_highlighted' => !$productFeature->is_highlighted,
        ]);
        
        return (new ProductFeatureResource($productFeature))
            ->additional(['message' => 'Product feature highlighted status updated successfully']);
    }
    
    /**
     * Toggle the public status of the specified product feature.
     *
     * @param ProductFeature $productFeature
     * @return ProductFeatureResource
     */
    public function togglePublic(ProductFeature $productFeature): ProductFeatureResource
    {
        $this->authorize('update', $productFeature);
        
        $productFeature->update([
            'is_public' => !$productFeature->is_public,
        ]);
        
        return (new ProductFeatureResource($productFeature))
            ->additional(['message' => 'Product feature public status updated successfully']);
    }
    
    /**
     * Get product features for a specific product.
     *
     * @param Product $product
     * @param Request $request
     * @return ResourceCollection
     */
    public function getProductFeatures(Product $product, Request $request): ResourceCollection
    {
        $productFeatures = $product->productFeatures()
            ->when(!$request->boolean('include_inactive'), function ($query) {
                $query->where('is_active', true);
            })
            ->when($request->boolean('only_public'), function ($query) {
                $query->where('is_public', true);
            })
            ->when($request->boolean('only_highlighted'), function ($query) {
                $query->where('is_highlighted', true);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->orderBy('sort_order')
            ->get();
            
        return ProductFeatureResource::collection($productFeatures);
    }
    
    /**
     * Batch update product features.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function batchUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'features' => ['required', 'array'],
            'features.*.id' => ['required', 'exists:product_features,id'],
            'features.*.sort_order' => ['required', 'integer', 'min:0'],
            'features.*.is_active' => ['nullable', 'boolean'],
            'features.*.is_highlighted' => ['nullable', 'boolean'],
            'features.*.is_public' => ['nullable', 'boolean'],
        ]);
        
        $updated = [];
        
        foreach ($request->features as $featureData) {
            $productFeature = ProductFeature::find($featureData['id']);
            $this->authorize('update', $productFeature);
            
            $data = array_intersect_key($featureData, array_flip(['sort_order', 'is_active', 'is_highlighted', 'is_public']));
            $productFeature->update($data);
            
            $updated[] = $productFeature;
        }
        
        return response()->json([
            'message' => 'Product features updated successfully',
            'data' => ProductFeatureResource::collection(collect($updated)),
        ]);
    }
}
