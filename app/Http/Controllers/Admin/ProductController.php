<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the products.
     */
    public function index(Request $request): View
    {
        $products = Product::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status === 'active');
            })
            ->when($request->filled('visibility'), function ($query) use ($request) {
                $query->where('visibility', $request->visibility);
            })
            ->when($request->filled('availability'), function ($query) use ($request) {
                $query->where('availability', $request->availability);
            })
            ->when($request->filled('is_featured'), function ($query) use ($request) {
                $query->where('is_featured', (bool)$request->is_featured);
            })
            ->when($request->filled('show_on_homepage'), function ($query) use ($request) {
                $query->where('show_on_homepage', (bool)$request->show_on_homepage);
            })
            ->when($request->filled('is_highlighted'), function ($query) use ($request) {
                $query->where('is_highlighted', (bool)$request->is_highlighted);
            })
            ->when($request->filled('published'), function ($query) use ($request) {
                if ($request->published === 'current') {
                    $query->published();
                } elseif ($request->published === 'scheduled') {
                    $now = Carbon::now();
                    $query->where('publish_at', '>', $now);
                } elseif ($request->published === 'expired') {
                    $now = Carbon::now();
                    $query->where('unpublish_at', '<=', $now);
                }
            })
            ->orderBy($request->sort ?? 'created_at', $request->direction ?? 'desc')
            ->withCount('pricingPlans')
            ->paginate(10)
            ->withQueryString();
            
        // Prepare view data for filters
        $visibilityOptions = [
            'public' => 'Public',
            'private' => 'Private',
            'restricted' => 'Restricted'
        ];
        
        $availabilityOptions = [
            'available' => 'Available',
            'coming_soon' => 'Coming Soon',
            'discontinued' => 'Discontinued'
        ];
            
        return view('admin.products.index', compact('products', 'visibilityOptions', 'availabilityOptions'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $visibilityOptions = [
            'public' => 'Public',
            'private' => 'Private',
            'restricted' => 'Restricted'
        ];
        
        $availabilityOptions = [
            'available' => 'Available',
            'coming_soon' => 'Coming Soon',
            'discontinued' => 'Discontinued'
        ];
        
        return view('admin.products.create', compact('visibilityOptions', 'availabilityOptions'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
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
        
        // Set default values for visibility options
        $validated['visibility'] = $validated['visibility'] ?? 'public';
        $validated['availability'] = $validated['availability'] ?? 'available';
        $validated['is_featured'] = $validated['is_featured'] ?? false;
        $validated['show_on_homepage'] = $validated['show_on_homepage'] ?? false;
        $validated['is_highlighted'] = $validated['is_highlighted'] ?? false;
        
        // Convert date strings to Carbon instances
        if (!empty($validated['publish_at'])) {
            $validated['publish_at'] = Carbon::parse($validated['publish_at']);
        }
        
        if (!empty($validated['unpublish_at'])) {
            $validated['unpublish_at'] = Carbon::parse($validated['unpublish_at']);
        }
        
        $product = Product::create($validated);
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): View
    {
        $product->load('pricingPlans');
        
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $visibilityOptions = [
            'public' => 'Public',
            'private' => 'Private',
            'restricted' => 'Restricted'
        ];
        
        $availabilityOptions = [
            'available' => 'Available',
            'coming_soon' => 'Coming Soon',
            'discontinued' => 'Discontinued'
        ];
        
        return view('admin.products.edit', compact('product', 'visibilityOptions', 'availabilityOptions'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
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
        
        // Convert date strings to Carbon instances
        if (!empty($validated['publish_at'])) {
            $validated['publish_at'] = Carbon::parse($validated['publish_at']);
        }
        
        if (!empty($validated['unpublish_at'])) {
            $validated['unpublish_at'] = Carbon::parse($validated['unpublish_at']);
        }
        
        $product->update($validated);
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Delete the product image if it exists
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
    
    /**
     * Toggle the status of the specified product.
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        
        $product->update([
            'is_active' => !$product->is_active,
        ]);
        
        return back()->with('success', 'Product status updated successfully.');
    }
    
    /**
     * Toggle the featured status of the specified product.
     */
    public function toggleFeatured(Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        
        $product->update([
            'is_featured' => !$product->is_featured,
        ]);
        
        return back()->with('success', 'Product featured status updated successfully.');
    }
    
    /**
     * Toggle the homepage status of the specified product.
     */
    public function toggleHomepage(Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        
        $product->update([
            'show_on_homepage' => !$product->show_on_homepage,
        ]);
        
        return back()->with('success', 'Product homepage status updated successfully.');
    }
    
    /**
     * Toggle the highlighted status of the specified product.
     */
    public function toggleHighlighted(Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        
        $product->update([
            'is_highlighted' => !$product->is_highlighted,
        ]);
        
        return back()->with('success', 'Product highlighted status updated successfully.');
    }
    
    /**
     * Update the visibility of the specified product.
     */
    public function updateVisibility(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        
        $request->validate([
            'visibility' => ['required', 'string', 'in:public,private,restricted'],
        ]);
        
        $product->update([
            'visibility' => $request->visibility,
        ]);
        
        return back()->with('success', 'Product visibility updated successfully.');
    }
    
    /**
     * Update the availability of the specified product.
     */
    public function updateAvailability(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        
        $request->validate([
            'availability' => ['required', 'string', 'in:coming_soon,available,discontinued'],
        ]);
        
        $product->update([
            'availability' => $request->availability,
        ]);
        
        return back()->with('success', 'Product availability updated successfully.');
    }
    
    /**
     * Update the scheduling of the specified product.
     */
    public function updateScheduling(Request $request, Product $product): RedirectResponse
    {
        $this->authorize('update', $product);
        
        $request->validate([
            'publish_at' => ['nullable', 'date'],
            'unpublish_at' => ['nullable', 'date', 'after:publish_at'],
        ]);
        
        $product->update([
            'publish_at' => $request->publish_at ? Carbon::parse($request->publish_at) : null,
            'unpublish_at' => $request->unpublish_at ? Carbon::parse($request->unpublish_at) : null,
        ]);
        
        return back()->with('success', 'Product scheduling updated successfully.');
    }
    
    /**
     * Bulk action on multiple products.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => [
                'required', 
                'string', 
                'in:activate,deactivate,delete,feature,unfeature,show_homepage,hide_homepage,highlight,unhighlight,set_public,set_private,set_restricted,set_available,set_coming_soon,set_discontinued'
            ],
            'products' => ['required', 'array'],
            'products.*' => ['exists:products,id'],
        ]);
        
        $products = Product::whereIn('id', $request->products)->get();
        
        foreach ($products as $product) {
            $this->authorize('update', $product);
            
            switch ($request->action) {
                case 'activate':
                    $product->update(['is_active' => true]);
                    break;
                case 'deactivate':
                    $product->update(['is_active' => false]);
                    break;
                case 'feature':
                    $product->update(['is_featured' => true]);
                    break;
                case 'unfeature':
                    $product->update(['is_featured' => false]);
                    break;
                case 'show_homepage':
                    $product->update(['show_on_homepage' => true]);
                    break;
                case 'hide_homepage':
                    $product->update(['show_on_homepage' => false]);
                    break;
                case 'highlight':
                    $product->update(['is_highlighted' => true]);
                    break;
                case 'unhighlight':
                    $product->update(['is_highlighted' => false]);
                    break;
                case 'set_public':
                    $product->update(['visibility' => 'public']);
                    break;
                case 'set_private':
                    $product->update(['visibility' => 'private']);
                    break;
                case 'set_restricted':
                    $product->update(['visibility' => 'restricted']);
                    break;
                case 'set_available':
                    $product->update(['availability' => 'available']);
                    break;
                case 'set_coming_soon':
                    $product->update(['availability' => 'coming_soon']);
                    break;
                case 'set_discontinued':
                    $product->update(['availability' => 'discontinued']);
                    break;
                case 'delete':
                    $this->authorize('delete', $product);
                    if ($product->image_path) {
                        Storage::disk('public')->delete($product->image_path);
                    }
                    $product->delete();
                    break;
            }
        }
        
        $actionMessages = [
            'activate' => 'activated',
            'deactivate' => 'deactivated',
            'delete' => 'deleted',
            'feature' => 'featured',
            'unfeature' => 'unfeatured',
            'show_homepage' => 'set to display on homepage',
            'hide_homepage' => 'removed from homepage',
            'highlight' => 'highlighted',
            'unhighlight' => 'unhighlighted',
            'set_public' => 'set to public visibility',
            'set_private' => 'set to private visibility',
            'set_restricted' => 'set to restricted visibility',
            'set_available' => 'set to available',
            'set_coming_soon' => 'set to coming soon',
            'set_discontinued' => 'set to discontinued',
        ];
        
        return back()->with('success', 'Selected products were ' . $actionMessages[$request->action] . ' successfully.');
    }
}
