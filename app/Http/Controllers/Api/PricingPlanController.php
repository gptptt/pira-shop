<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePricingPlanRequest;
use App\Http\Requests\Admin\UpdatePricingPlanRequest;
use App\Http\Resources\PricingPlanResource;
use App\Models\PricingPlan;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PricingPlanController extends Controller
{
    /**
     * Display a listing of the pricing plans.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request): ResourceCollection
    {
        $pricingPlans = PricingPlan::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('product_id'), function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->when($request->filled('billing_cycle'), function ($query) use ($request) {
                $query->where('billing_cycle', $request->billing_cycle);
            })
            ->when($request->filled('is_featured'), function ($query) use ($request) {
                $query->where('is_featured', (bool)$request->is_featured);
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
                $query->orderBy('sort_order', 'asc')->orderBy('price', 'asc');
            })
            ->paginate($request->per_page ?? 10)
            ->withQueryString();

        return PricingPlanResource::collection($pricingPlans);
    }

    /**
     * Store a newly created pricing plan in storage.
     *
     * @param StorePricingPlanRequest $request
     * @return JsonResponse
     */
    public function store(StorePricingPlanRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        // Convert features from string to array if needed
        if (isset($validated['features']) && is_string($validated['features'])) {
            $validated['features'] = array_map('trim', explode("\n", $validated['features']));
        }
        
        // Set the appropriate price based on billing cycle
        if (isset($validated['billing_cycle'])) {
            switch ($validated['billing_cycle']) {
                case 'monthly':
                    $validated['monthly_price'] = $validated['price'];
                    break;
                case 'yearly':
                    $validated['yearly_price'] = $validated['price'];
                    // Optionally set monthly equivalent
                    if (!isset($validated['monthly_price'])) {
                        $validated['monthly_price'] = $validated['price'] / 12;
                    }
                    break;
                case 'custom':
                    $validated['custom_price'] = $validated['price'];
                    break;
            }
        }
        
        $pricingPlan = PricingPlan::create($validated);
        
        return (new PricingPlanResource($pricingPlan))
            ->additional(['message' => 'Pricing plan created successfully'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified pricing plan.
     *
     * @param PricingPlan $pricingPlan
     * @param Request $request
     * @return PricingPlanResource
     */
    public function show(PricingPlan $pricingPlan, Request $request): PricingPlanResource
    {
        if ($request->boolean('with_product')) {
            $pricingPlan->load('product');
        }
        
        return new PricingPlanResource($pricingPlan);
    }

    /**
     * Update the specified pricing plan in storage.
     *
     * @param UpdatePricingPlanRequest $request
     * @param PricingPlan $pricingPlan
     * @return PricingPlanResource
     */
    public function update(UpdatePricingPlanRequest $request, PricingPlan $pricingPlan): PricingPlanResource
    {
        $validated = $request->validated();
        
        // Convert features from string to array if needed
        if (isset($validated['features']) && is_string($validated['features'])) {
            $validated['features'] = array_map('trim', explode("\n", $validated['features']));
        }
        
        // Update the appropriate price based on billing cycle
        if (isset($validated['billing_cycle'])) {
            switch ($validated['billing_cycle']) {
                case 'monthly':
                    $validated['monthly_price'] = $validated['price'] ?? $pricingPlan->price;
                    break;
                case 'yearly':
                    $validated['yearly_price'] = $validated['price'] ?? $pricingPlan->price;
                    // Optionally update monthly equivalent
                    if (isset($validated['price']) && !isset($validated['monthly_price'])) {
                        $validated['monthly_price'] = $validated['price'] / 12;
                    }
                    break;
                case 'custom':
                    $validated['custom_price'] = $validated['price'] ?? $pricingPlan->price;
                    break;
            }
        }
        
        $pricingPlan->update($validated);
        
        return (new PricingPlanResource($pricingPlan))
            ->additional(['message' => 'Pricing plan updated successfully']);
    }

    /**
     * Remove the specified pricing plan from storage.
     *
     * @param PricingPlan $pricingPlan
     * @return JsonResponse
     */
    public function destroy(PricingPlan $pricingPlan): JsonResponse
    {
        $this->authorize('delete', $pricingPlan);
        
        $pricingPlan->delete();
        
        return response()->json([
            'message' => 'Pricing plan deleted successfully',
        ]);
    }
    
    /**
     * Toggle the status of the specified pricing plan.
     *
     * @param PricingPlan $pricingPlan
     * @return PricingPlanResource
     */
    public function toggleStatus(PricingPlan $pricingPlan): PricingPlanResource
    {
        $this->authorize('update', $pricingPlan);
        
        $pricingPlan->update([
            'is_active' => !$pricingPlan->is_active,
        ]);
        
        return (new PricingPlanResource($pricingPlan))
            ->additional(['message' => 'Pricing plan status updated successfully']);
    }
    
    /**
     * Toggle the featured status of the specified pricing plan.
     *
     * @param PricingPlan $pricingPlan
     * @return PricingPlanResource
     */
    public function toggleFeatured(PricingPlan $pricingPlan): PricingPlanResource
    {
        $this->authorize('update', $pricingPlan);
        
        $pricingPlan->update([
            'is_featured' => !$pricingPlan->is_featured,
        ]);
        
        return (new PricingPlanResource($pricingPlan))
            ->additional(['message' => 'Pricing plan featured status updated successfully']);
    }
    
    /**
     * Get pricing plans for a specific product.
     *
     * @param Product $product
     * @param Request $request
     * @return ResourceCollection
     */
    public function getProductPlans(Product $product, Request $request): ResourceCollection
    {
        $pricingPlans = $product->pricingPlans()
            ->when(!$request->boolean('include_inactive'), function ($query) {
                $query->where('is_active', true);
            })
            ->when($request->filled('billing_cycle'), function ($query) use ($request) {
                $query->where('billing_cycle', $request->billing_cycle);
            })
            ->orderBy('sort_order')
            ->orderBy('price')
            ->get();
            
        return PricingPlanResource::collection($pricingPlans);
    }
    
    /**
     * Create multiple pricing tiers for a product.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createMultipleTiers(Request $request): JsonResponse
    {
        $this->authorize('create', PricingPlan::class);
        
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'tiers' => ['required', 'array', 'min:1'],
            'tiers.*.name' => ['required', 'string', 'max:255'],
            'tiers.*.monthly_price' => ['required', 'numeric', 'min:0'],
            'tiers.*.yearly_price' => ['required', 'numeric', 'min:0'],
            'tiers.*.features' => ['nullable', 'string'],
            'tiers.*.is_featured' => ['nullable', 'boolean'],
            'tiers.*.trial_days' => ['nullable', 'integer', 'min:0'],
        ]);
        
        $product = Product::findOrFail($request->product_id);
        $createdPlans = [];
        
        // Create multiple pricing plans
        foreach ($request->tiers as $tier) {
            // Create monthly plan
            $monthlyPlan = PricingPlan::create([
                'product_id' => $product->id,
                'name' => $tier['name'] . ' (Monthly)',
                'slug' => Str::slug($tier['name'] . '-monthly'),
                'price' => $tier['monthly_price'],
                'monthly_price' => $tier['monthly_price'],
                'yearly_price' => $tier['yearly_price'],
                'billing_cycle' => 'monthly',
                'features' => array_map('trim', explode("\n", $tier['features'] ?? '')),
                'is_featured' => $tier['is_featured'] ?? false,
                'is_active' => true,
                'trial_days' => $tier['trial_days'] ?? 0,
                'metadata' => [
                    'tier_group' => $tier['name'],
                ],
            ]);
            
            // Create yearly plan
            $yearlyPlan = PricingPlan::create([
                'product_id' => $product->id,
                'name' => $tier['name'] . ' (Yearly)',
                'slug' => Str::slug($tier['name'] . '-yearly'),
                'price' => $tier['yearly_price'],
                'monthly_price' => $tier['monthly_price'],
                'yearly_price' => $tier['yearly_price'],
                'billing_cycle' => 'yearly',
                'features' => array_map('trim', explode("\n", $tier['features'] ?? '')),
                'is_featured' => $tier['is_featured'] ?? false,
                'is_active' => true,
                'trial_days' => $tier['trial_days'] ?? 0,
                'metadata' => [
                    'tier_group' => $tier['name'],
                ],
            ]);
            
            $createdPlans[] = $monthlyPlan;
            $createdPlans[] = $yearlyPlan;
        }
        
        return response()->json([
            'message' => 'Multiple pricing tiers created successfully',
            'data' => PricingPlanResource::collection(collect($createdPlans)),
        ], Response::HTTP_CREATED);
    }
    
    /**
     * Create an enterprise tier for a product.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createEnterpriseTier(Request $request): JsonResponse
    {
        $this->authorize('create', PricingPlan::class);
        
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'features' => ['nullable', 'string'],
            'contact_text' => ['nullable', 'string', 'max:255'],
            'contact_url' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        // Create enterprise plan
        $enterprisePlan = PricingPlan::create([
            'product_id' => $product->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => 0, // Typically not shown for enterprise
            'billing_cycle' => 'custom',
            'features' => array_map('trim', explode("\n", $request->features ?? '')),
            'is_featured' => $request->is_featured ?? false,
            'is_active' => true,
            'metadata' => [
                'is_enterprise' => true,
                'contact_text' => $request->contact_text ?? 'Contact Us',
                'contact_url' => $request->contact_url,
            ],
        ]);
        
        return (new PricingPlanResource($enterprisePlan))
            ->additional(['message' => 'Enterprise tier created successfully'])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
    
    /**
     * Get all pricing tiers grouped by product for comparison.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPricingComparison(Request $request): JsonResponse
    {
        $result = [];
        
        $products = Product::where('is_active', true)
            ->when($request->filled('product_ids'), function ($query) use ($request) {
                $query->whereIn('id', explode(',', $request->product_ids));
            })
            ->orderBy('sort_order')
            ->get();
            
        foreach ($products as $product) {
            $plansByBillingCycle = [];
            
            $pricingPlans = $product->pricingPlans()
                ->where('is_active', true)
                ->when($request->filled('billing_cycle'), function ($query) use ($request) {
                    $query->where('billing_cycle', $request->billing_cycle);
                })
                ->orderBy('sort_order')
                ->orderBy('price')
                ->get();
                
            // Group plans by billing cycle
            foreach ($pricingPlans as $plan) {
                $plansByBillingCycle[$plan->billing_cycle][] = new PricingPlanResource($plan);
            }
            
            $result[] = [
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->short_description,
                ],
                'pricing_plans' => $plansByBillingCycle,
            ];
        }
        
        return response()->json([
            'data' => $result,
        ]);
    }
}
