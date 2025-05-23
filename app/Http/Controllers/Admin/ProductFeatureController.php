<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductFeatureRequest;
use App\Http\Requests\Admin\UpdateProductFeatureRequest;
use App\Models\Product;
use App\Models\ProductFeature;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductFeatureController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(ProductFeature::class, 'product_feature');
    }

    /**
     * Display a listing of the product features.
     */
    public function index(Request $request): View
    {
        $productFeatures = ProductFeature::query()
            ->with('product')
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
            ->orderBy($request->sort ?? 'sort_order', $request->direction ?? 'asc')
            ->paginate(15)
            ->withQueryString();
            
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $types = [
            'boolean' => 'Boolean',
            'numeric' => 'Numeric',
            'text' => 'Text',
            'list' => 'List',
        ];
            
        return view('admin.product-features.index', compact('productFeatures', 'products', 'types'));
    }

    /**
     * Show the form for creating a new product feature.
     */
    public function create(Request $request): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $productId = $request->product_id ?? null;
        $types = [
            'boolean' => 'Boolean',
            'numeric' => 'Numeric',
            'text' => 'Text',
            'list' => 'List',
        ];
        
        return view('admin.product-features.create', compact('products', 'productId', 'types'));
    }

    /**
     * Store a newly created product feature in storage.
     */
    public function store(StoreProductFeatureRequest $request): RedirectResponse
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
        
        return redirect()
            ->route('admin.product-features.index', ['product_id' => $productFeature->product_id])
            ->with('success', 'Product feature created successfully.');
    }

    /**
     * Display the specified product feature.
     */
    public function show(ProductFeature $productFeature): View
    {
        $productFeature->load('product');
        
        return view('admin.product-features.show', compact('productFeature'));
    }

    /**
     * Show the form for editing the specified product feature.
     */
    public function edit(ProductFeature $productFeature): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->pluck('name', 'id');
        $types = [
            'boolean' => 'Boolean',
            'numeric' => 'Numeric',
            'text' => 'Text',
            'list' => 'List',
        ];
        
        return view('admin.product-features.edit', compact('productFeature', 'products', 'types'));
    }

    /**
     * Update the specified product feature in storage.
     */
    public function update(UpdateProductFeatureRequest $request, ProductFeature $productFeature): RedirectResponse
    {
        $validated = $request->validated();
        
        // Convert options from string to array if needed
        if (isset($validated['options']) && is_string($validated['options'])) {
            $validated['options'] = array_map('trim', explode("\n", $validated['options']));
        }
        
        $productFeature->update($validated);
        
        return redirect()
            ->route('admin.product-features.index', ['product_id' => $productFeature->product_id])
            ->with('success', 'Product feature updated successfully.');
    }

    /**
     * Remove the specified product feature from storage.
     */
    public function destroy(ProductFeature $productFeature): RedirectResponse
    {
        $productId = $productFeature->product_id;
        $productFeature->delete();
        
        return redirect()
            ->route('admin.product-features.index', ['product_id' => $productId])
            ->with('success', 'Product feature deleted successfully.');
    }
    
    /**
     * Toggle the status of the specified product feature.
     */
    public function toggleStatus(ProductFeature $productFeature): RedirectResponse
    {
        $this->authorize('update', $productFeature);
        
        $productFeature->update([
            'is_active' => !$productFeature->is_active,
        ]);
        
        return back()->with('success', 'Product feature status updated successfully.');
    }
    
    /**
     * Toggle the highlighted status of the specified product feature.
     */
    public function toggleHighlighted(ProductFeature $productFeature): RedirectResponse
    {
        $this->authorize('update', $productFeature);
        
        $productFeature->update([
            'is_highlighted' => !$productFeature->is_highlighted,
        ]);
        
        return back()->with('success', 'Product feature highlighted status updated successfully.');
    }
    
    /**
     * Toggle the public status of the specified product feature.
     */
    public function togglePublic(ProductFeature $productFeature): RedirectResponse
    {
        $this->authorize('update', $productFeature);
        
        $productFeature->update([
            'is_public' => !$productFeature->is_public,
        ]);
        
        return back()->with('success', 'Product feature public status updated successfully.');
    }
    
    /**
     * Reorder product features.
     */
    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'features' => ['required', 'array'],
            'features.*.id' => ['required', 'exists:product_features,id'],
            'features.*.order' => ['required', 'integer', 'min:0'],
        ]);
        
        foreach ($request->features as $feature) {
            $productFeature = ProductFeature::findOrFail($feature['id']);
            $this->authorize('update', $productFeature);
            
            $productFeature->update([
                'sort_order' => $feature['order'],
            ]);
        }
        
        return back()->with('success', 'Product features reordered successfully.');
    }
    
    /**
     * Bulk action on multiple product features.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => ['required', 'string', 'in:activate,deactivate,highlight,unhighlight,make_public,make_private,delete'],
            'product_features' => ['required', 'array'],
            'product_features.*' => ['exists:product_features,id'],
        ]);
        
        $productFeatures = ProductFeature::whereIn('id', $request->product_features)->get();
        $productId = null;
        
        foreach ($productFeatures as $productFeature) {
            $this->authorize('update', $productFeature);
            $productId = $productFeature->product_id; // Save for redirect
            
            switch ($request->action) {
                case 'activate':
                    $productFeature->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    $productFeature->update(['is_active' => false]);
                    break;
                case 'highlight':
                    $productFeature->update(['is_highlighted' => true]);
                    break;
                case 'unhighlight':
                    $productFeature->update(['is_highlighted' => false]);
                    break;
                case 'make_public':
                    $productFeature->update(['is_public' => true]);
                    break;
                case 'make_private':
                    $productFeature->update(['is_public' => false]);
                    break;
                case 'delete':
                    $this->authorize('delete', $productFeature);
                    $productFeature->delete();
                    break;
            }
        }
        
        $actionMessages = [
            'activate' => 'activated',
            'deactivate' => 'deactivated',
            'highlight' => 'highlighted',
            'unhighlight' => 'unhighlighted',
            'make_public' => 'made public',
            'make_private' => 'made private',
            'delete' => 'deleted',
        ];
        
        return redirect()
            ->route('admin.product-features.index', ['product_id' => $productId])
            ->with('success', 'Selected product features were ' . $actionMessages[$request->action] . ' successfully.');
    }
}
