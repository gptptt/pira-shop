<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/* REF-PR-UTIL-001: Dashboard data export functionality */
class ExportController extends Controller
{
    protected $exportService;
    
    /**
     * Create a new controller instance.
     */
    public function __construct(ExportService $exportService)
    {
        $this->middleware('admin');
        $this->exportService = $exportService;
    }
    
    /**
     * Export dashboard data in CSV format
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportCsv(Request $request)
    {
        $type = $request->get('type', 'orders');
        $data = $this->getDashboardData($type);
        
        [$exportData, $headers] = $this->exportService->prepareDashboardData($type, $data);
        
        return $this->exportService->toCsv($exportData, $headers, "dashboard_{$type}_" . date('Y-m-d'));
    }
    
    /**
     * Export dashboard data in Excel format
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportExcel(Request $request)
    {
        $type = $request->get('type', 'orders');
        $data = $this->getDashboardData($type);
        
        [$exportData, $headers] = $this->exportService->prepareDashboardData($type, $data);
        
        return $this->exportService->toExcel($exportData, $headers, "dashboard_{$type}_" . date('Y-m-d'));
    }
    
    /**
     * Export dashboard data in PDF format
     *
     * @param Request $request
     * @return mixed
     */
    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'orders');
        $data = $this->getDashboardData($type);
        
        [$exportData, $headers] = $this->exportService->prepareDashboardData($type, $data);
        
        return $this->exportService->toPdf(
            $exportData, 
            $headers, 
            "dashboard_{$type}_" . date('Y-m-d'),
            "Dashboard {$type} Report"
        );
    }
    
    /**
     * Get dashboard data based on type
     *
     * @param string $type
     * @return mixed
     */
    private function getDashboardData(string $type)
    {
        switch ($type) {
            case 'orders':
                return $this->getOrdersData();
            case 'customers':
                return $this->getCustomersData();
            case 'revenue':
                return $this->getRevenueData();
            case 'products':
                return $this->getProductsData();
            default:
                return collect([]);
        }
    }
    
    /**
     * Get orders data for export
     *
     * @return \Illuminate\Support\Collection
     */
    private function getOrdersData()
    {
        // In a real application, this would come from the database
        // This is using the mock data from AdminController for consistency
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
     * Get customers data for export
     *
     * @return \Illuminate\Support\Collection
     */
    private function getCustomersData()
    {
        // In a real application, get this from the database
        return User::whereHas('roles', function($query) {
            $query->where('name', 'customer');
        })->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"), 'email', 'created_at')
          ->limit(100)
          ->get();
    }
    
    /**
     * Get revenue data for export
     *
     * @return array
     */
    private function getRevenueData()
    {
        // In a real application, get this from the database
        // This is using the mock data from AdminController for consistency
        return [5200, 6100, 7800, 8900, 9100, 10200, 11500, 12200, 11800, 13100, 14200, 12500];
    }
    
    /**
     * Get products data for export
     *
     * @return \Illuminate\Support\Collection
     */
    private function getProductsData()
    {
        // In a real application, get this from the database
        // This is using the mock data from AdminController for consistency
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