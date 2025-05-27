<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mock data for demonstration purposes
        // In a real application, these would come from database queries
        
        // Dashboard stats
        $data = [
            'totalCustomers' => User::whereHas('roles', function($query) {
                $query->where('name', 'customer');
            })->count(),
            'monthlyRevenue' => 12500.75,
            'activeSubscriptions' => 85,
            'pendingOrders' => 7,
            
            // Revenue chart data - last 12 months
            'revenueData' => [5200, 6100, 7800, 8900, 9100, 10200, 11500, 12200, 11800, 13100, 14200, 12500],
            
            // New customers data - last 6 months
            'newCustomersData' => [24, 18, 31, 27, 36, 42],
            
            // Recent orders
            'recentOrders' => $this->getMockOrders(),
            
            // Top products
            'topProducts' => $this->getMockProducts(),
        ];
        
        return view('admin.dashboard.index', $data);
    }
    
    /**
     * Refresh the dashboard data (used for AJAX updates).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Mock data for demonstration purposes
        // In a real application, these would come from database queries
        
        // Dashboard stats
        $data = [
            'totalCustomers' => User::whereHas('roles', function($query) {
                $query->where('name', 'customer');
            })->count(),
            'monthlyRevenue' => 12500.75,
            'activeSubscriptions' => 85,
            'pendingOrders' => 7,
            
            // Revenue chart data - last 12 months
            'revenueData' => [5200, 6100, 7800, 8900, 9100, 10200, 11500, 12200, 11800, 13100, 14200, 12500],
            
            // New customers data - last 6 months
            'newCustomersData' => [24, 18, 31, 27, 36, 42],
            
            // Recent orders
            'recentOrders' => $this->getMockOrders()->toArray(),
            
            // Top products
            'topProducts' => $this->getMockProducts()->toArray(),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => 'Dashboard data refreshed successfully',
            'timestamp' => now()->toDateTimeString()
        ]);
    }
    
    /**
     * Get mock order data for demonstration purposes.
     *
     * @return array
     */
    private function getMockOrders()
    {
        // This would normally come from the database
        return collect([
            (object)[
                'id' => 1001,
                'user' => (object)['full_name' => 'John Smith'],
                'product' => (object)['name' => 'Premium Plan'],
                'amount' => 99.99,
                'status' => 'completed',
                'created_at' => now()->subDays(2),
            ],
            (object)[
                'id' => 1002,
                'user' => (object)['full_name' => 'Alice Johnson'],
                'product' => (object)['name' => 'Basic Plan'],
                'amount' => 49.99,
                'status' => 'pending',
                'created_at' => now()->subDays(1),
            ],
            (object)[
                'id' => 1003,
                'user' => (object)['full_name' => 'Robert Brown'],
                'product' => (object)['name' => 'Enterprise Plan'],
                'amount' => 199.99,
                'status' => 'completed',
                'created_at' => now()->subHours(12),
            ],
            (object)[
                'id' => 1004,
                'user' => (object)['full_name' => 'Emily Davis'],
                'product' => (object)['name' => 'Premium Plan'],
                'amount' => 99.99,
                'status' => 'failed',
                'created_at' => now()->subHours(6),
            ],
            (object)[
                'id' => 1005,
                'user' => (object)['full_name' => 'Michael Wilson'],
                'product' => (object)['name' => 'Basic Plan'],
                'amount' => 49.99,
                'status' => 'pending',
                'created_at' => now()->subHours(3),
            ],
        ]);
    }
    
    /**
     * Get mock product data for demonstration purposes.
     *
     * @return array
     */
    private function getMockProducts()
    {
        // This would normally come from the database
        return collect([
            (object)[
                'name' => 'Premium Plan',
                'sales_count' => 42,
                'total_revenue' => 4199.58,
            ],
            (object)[
                'name' => 'Basic Plan',
                'sales_count' => 78,
                'total_revenue' => 3899.22,
            ],
            (object)[
                'name' => 'Enterprise Plan',
                'sales_count' => 16,
                'total_revenue' => 3199.84,
            ],
            (object)[
                'name' => 'Advanced Plan',
                'sales_count' => 29,
                'total_revenue' => 2899.71,
            ],
        ]);
    }
}
