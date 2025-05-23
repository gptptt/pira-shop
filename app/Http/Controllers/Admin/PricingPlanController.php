<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePricingPlanRequest;
use App\Http\Requests\Admin\UpdatePricingPlanRequest;
use App\Models\PricingPlan;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PricingPlanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(PricingPlan::class, 'pricing_plan');
    }

    /**
     * Display a listing of the pricing plans.
     */
    public function index(Request $request): View
    {
        $pricingPlans = PricingPlan::query()
            ->with('product')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhereHas('product', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
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
            ->orderBy($request->sort ?? 'created_at', $request->direction ?? 'desc')
            ->paginate(10)
            ->withQueryString();
            
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
            
        return view('admin.pricing-plans.index', compact('pricingPlans', 'products'));
    }

    /**
     * Show the form for creating a new pricing plan.
     */
    public function create(Request $request): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $productId = $request->product_id ?? null;
        $billingCycle = $request->billing_cycle ?? 'monthly';
        
        return view('admin.pricing-plans.create', compact('products', 'productId', 'billingCycle'));
    }

    /**
     * Store a newly created pricing plan in storage.
     */
    public function store(StorePricingPlanRequest $request): RedirectResponse
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
        
        return redirect()
            ->route('admin.pricing-plans.index', ['product_id' => $pricingPlan->product_id])
            ->with('success', 'Pricing plan created successfully.');
    }

    /**
     * Display the specified pricing plan.
     */
    public function show(PricingPlan $pricingPlan): View
    {
        $pricingPlan->load('product');
        
        return view('admin.pricing-plans.show', compact('pricingPlan'));
    }

    /**
     * Show the form for editing the specified pricing plan.
     */
    public function edit(PricingPlan $pricingPlan): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        
        return view('admin.pricing-plans.edit', compact('pricingPlan', 'products'));
    }

    /**
     * Update the specified pricing plan in storage.
     */
    public function update(UpdatePricingPlanRequest $request, PricingPlan $pricingPlan): RedirectResponse
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
        
        return redirect()
            ->route('admin.pricing-plans.index', ['product_id' => $pricingPlan->product_id])
            ->with('success', 'Pricing plan updated successfully.');
    }

    /**
     * Remove the specified pricing plan from storage.
     */
    public function destroy(PricingPlan $pricingPlan): RedirectResponse
    {
        $productId = $pricingPlan->product_id;
        $pricingPlan->delete();
        
        return redirect()
            ->route('admin.pricing-plans.index', ['product_id' => $productId])
            ->with('success', 'Pricing plan deleted successfully.');
    }
    
    /**
     * Toggle the status of the specified pricing plan.
     */
    public function toggleStatus(PricingPlan $pricingPlan): RedirectResponse
    {
        $this->authorize('update', $pricingPlan);
        
        $pricingPlan->update([
            'is_active' => !$pricingPlan->is_active,
        ]);
        
        return back()->with('success', 'Pricing plan status updated successfully.');
    }
    
    /**
     * Toggle the featured status of the specified pricing plan.
     */
    public function toggleFeatured(PricingPlan $pricingPlan): RedirectResponse
    {
        $this->authorize('update', $pricingPlan);
        
        $pricingPlan->update([
            'is_featured' => !$pricingPlan->is_featured,
        ]);
        
        return back()->with('success', 'Pricing plan featured status updated successfully.');
    }
    
    /**
     * Create multiple pricing tiers for a product at once.
     */
    public function createMultipleTiers(Request $request): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $productId = $request->product_id ?? null;
        
        return view('admin.pricing-plans.create-multiple', compact('products', 'productId'));
    }
    
    /**
     * Store multiple pricing tiers for a product.
     */
    public function storeMultipleTiers(Request $request): RedirectResponse
    {
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
        
        // Create multiple pricing plans
        foreach ($request->tiers as $tier) {
            // Create monthly plan
            PricingPlan::create([
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
            PricingPlan::create([
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
        }
        
        return redirect()
            ->route('admin.pricing-plans.index', ['product_id' => $product->id])
            ->with('success', 'Multiple pricing tiers created successfully.');
    }
    
    /**
     * Create an enterprise tier for a product.
     */
    public function createEnterpriseTier(Request $request): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $productId = $request->product_id ?? null;
        
        return view('admin.pricing-plans.create-enterprise', compact('products', 'productId'));
    }
    
    /**
     * Store an enterprise tier for a product.
     */
    public function storeEnterpriseTier(Request $request): RedirectResponse
    {
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
        PricingPlan::create([
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
        
        return redirect()
            ->route('admin.pricing-plans.index', ['product_id' => $product->id])
            ->with('success', 'Enterprise tier created successfully.');
    }
    
    /**
     * Bulk action on multiple pricing plans.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'string', 'in:activate,deactivate,delete,feature,unfeature'],
            'pricing_plans' => ['required', 'array'],
            'pricing_plans.*' => ['exists:price_plans,id'],
        ]);
        
        $pricingPlans = PricingPlan::whereIn('id', $request->pricing_plans)->get();
        $productId = null;
        
        foreach ($pricingPlans as $pricingPlan) {
            $this->authorize('update', $pricingPlan);
            $productId = $pricingPlan->product_id; // Save for redirect
            
            switch ($request->action) {
                case 'activate':
                    $pricingPlan->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    $pricingPlan->update(['is_active' => false]);
                    break;
                case 'feature':
                    $pricingPlan->update(['is_featured' => true]);
                    break;
                case 'unfeature':
                    $pricingPlan->update(['is_featured' => false]);
                    break;
                case 'delete':
                    $this->authorize('delete', $pricingPlan);
                    $pricingPlan->delete();
                    break;
            }
        }
        
        $actionMessages = [
            'activate' => 'activated',
            'deactivate' => 'deactivated',
            'feature' => 'set as featured',
            'unfeature' => 'unfeatured',
            'delete' => 'deleted',
        ];
        
        return redirect()
            ->route('admin.pricing-plans.index', ['product_id' => $productId])
            ->with('success', 'Selected pricing plans were ' . $actionMessages[$request->action] . ' successfully.');
    }
}
