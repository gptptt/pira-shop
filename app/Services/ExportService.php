<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;

/* REF-PR-UTIL-001: Dashboard data export functionality */
class ExportService
{
    /**
     * Export data to CSV format
     *
     * @param array $data The data to export
     * @param array $headers The column headers
     * @param string $filename The name of the file to export
     * @return StreamedResponse
     */
    public function toCsv(array $data, array $headers, string $filename): StreamedResponse
    {
        $callback = function () use ($data, $headers) {
            $handle = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($handle, $headers);
            
            // Add data
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
        };
        
        $response = new StreamedResponse($callback);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');
        
        return $response;
    }
    
    /**
     * Export data to Excel format
     * 
     * @param array $data The data to export
     * @param array $headers The column headers
     * @param string $filename The name of the file to export
     * @return StreamedResponse
     */
    public function toExcel(array $data, array $headers, string $filename): StreamedResponse
    {
        // For actual Excel export, we'd use a library like Laravel Excel
        // But for now, we'll just use CSV with a different extension
        $callback = function () use ($data, $headers) {
            $handle = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($handle, $headers);
            
            // Add data
            foreach ($data as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
        };
        
        $response = new StreamedResponse($callback);
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.xls"');
        
        return $response;
    }
    
    /**
     * Export data to PDF format
     * 
     * @param array $data The data to export
     * @param array $headers The column headers
     * @param string $filename The name of the file to export
     * @param string $title The title of the PDF
     * @return mixed
     */
    public function toPdf(array $data, array $headers, string $filename, string $title): mixed
    {
        // This is a placeholder. In a real implementation, we would use a PDF library
        // such as Dompdf, TCPDF, or mPDF to generate the PDF.
        // Since we don't have any PDF library installed, we'll just return a text response for now.
        
        return response("PDF export is not implemented yet. Install a PDF library to enable this feature.")
            ->header('Content-Type', 'text/plain');
    }
    
    /**
     * Prepare dashboard data for export
     * 
     * @param string $type The type of data to export (orders, customers, revenue)
     * @param mixed $data The data to prepare
     * @return array [data, headers]
     */
    public function prepareDashboardData(string $type, $data): array
    {
        switch ($type) {
            case 'orders':
                return $this->prepareOrdersData($data);
            case 'customers':
                return $this->prepareCustomersData($data);
            case 'revenue':
                return $this->prepareRevenueData($data);
            case 'products':
                return $this->prepareProductsData($data);
            default:
                return [[], []];
        }
    }
    
    /**
     * Prepare orders data for export
     * 
     * @param Collection $orders
     * @return array
     */
    private function prepareOrdersData($orders): array
    {
        $headers = ['ID', 'Customer', 'Product', 'Amount', 'Status', 'Date'];
        
        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                $order->id,
                $order->user->full_name ?? 'N/A',
                $order->product->name ?? 'N/A',
                $order->amount,
                $order->status,
                $order->created_at->format('Y-m-d H:i:s')
            ];
        }
        
        return [$data, $headers];
    }
    
    /**
     * Prepare customers data for export
     * 
     * @param Collection $customers
     * @return array
     */
    private function prepareCustomersData($customers): array
    {
        $headers = ['ID', 'Name', 'Email', 'Registration Date'];
        
        $data = [];
        foreach ($customers as $customer) {
            $data[] = [
                $customer->id,
                $customer->full_name,
                $customer->email,
                $customer->created_at->format('Y-m-d H:i:s')
            ];
        }
        
        return [$data, $headers];
    }
    
    /**
     * Prepare revenue data for export
     * 
     * @param array $revenue
     * @return array
     */
    private function prepareRevenueData($revenue): array
    {
        $headers = ['Month', 'Revenue'];
        
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                   'July', 'August', 'September', 'October', 'November', 'December'];
        
        $data = [];
        foreach ($revenue as $index => $amount) {
            $data[] = [
                $months[$index] ?? "Month " . ($index + 1),
                $amount
            ];
        }
        
        return [$data, $headers];
    }
    
    /**
     * Prepare products data for export
     * 
     * @param Collection $products
     * @return array
     */
    private function prepareProductsData($products): array
    {
        $headers = ['Name', 'Sales Count', 'Total Revenue'];
        
        $data = [];
        foreach ($products as $product) {
            $data[] = [
                $product->name,
                $product->sales_count,
                $product->total_revenue
            ];
        }
        
        return [$data, $headers];
    }
} 