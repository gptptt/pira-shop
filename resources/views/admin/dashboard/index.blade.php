@extends('layouts.admin')

@section('content')
<div class="w-full">
    @component('shared.components.dashboard.main', [
        'title' => 'Admin Dashboard',
        'subtitle' => 'Welcome back, ' . auth()->user()->first_name . '!'
    ])
        @slot('actions')
            <div class="relative inline-block text-left" x-data="{ open: false }">
                <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-download mr-2"></i> Export
                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open" 
                    @click.away="open = false"
                    class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" 
                    role="menu" 
                    aria-orientation="vertical">
                    <div class="py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">CSV</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Excel</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">PDF</a>
                    </div>
                </div>
            </div>
            
            <button type="button" class="inline-flex items-center px-3 py-2 border border-indigo-500 shadow-sm text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-sync-alt mr-2"></i> Refresh
            </button>
        @endslot

        @slot('stats')
            <div>
                @component('shared.components.dashboard.stats-card', [
                    'title' => 'Total Customers',
                    'value' => $totalCustomers ?? 0,
                    'icon' => 'users',
                    'color' => 'primary',
                    'route' => route('admin.users.index')
                ])
                @endcomponent
            </div>
            <div>
                @component('shared.components.dashboard.stats-card', [
                    'title' => 'Monthly Revenue',
                    'value' => '$' . number_format($monthlyRevenue ?? 0, 2),
                    'icon' => 'dollar-sign',
                    'color' => 'success',
                    'route' => '#'
                ])
                @endcomponent
            </div>
            <div>
                @component('shared.components.dashboard.stats-card', [
                    'title' => 'Active Subscriptions',
                    'value' => $activeSubscriptions ?? 0,
                    'icon' => 'repeat',
                    'color' => 'info',
                    'route' => '#'
                ])
                @endcomponent
            </div>
            <div>
                @component('shared.components.dashboard.stats-card', [
                    'title' => 'Pending Orders',
                    'value' => $pendingOrders ?? 0,
                    'icon' => 'shopping-cart',
                    'color' => 'warning',
                    'route' => '#'
                ])
                @endcomponent
            </div>
        @endslot

        @slot('primary')
            @component('shared.components.dashboard.chart', [
                'id' => 'revenueChart',
                'title' => 'Revenue (Last 12 Months)',
                'type' => 'line',
                'height' => '300'
            ])
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [{
                            label: 'Revenue',
                            data: [{{ implode(', ', $revenueData ?? array_fill(0, 12, 0)) }}],
                            borderColor: '#4f46e5',
                            tension: 0.3,
                            fill: {
                                target: 'origin',
                                above: 'rgba(79, 70, 229, 0.1)',
                            }
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            @endcomponent

            <x-card title="Recent Orders" class="shadow-sm">
                <x-slot:headerActions>
                    <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View All</a>
                </x-slot:headerActions>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($recentOrders) && count($recentOrders) > 0)
                                @foreach($recentOrders as $order)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->user->full_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->product->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($order->amount, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($order->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                            @elseif($order->status === 'pending')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($order->status === 'failed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Failed</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($order->status) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No recent orders found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </x-card>
        @endslot

        @slot('secondary')
            @component('shared.components.dashboard.chart', [
                'id' => 'customerChart',
                'title' => 'New Customers',
                'type' => 'bar',
                'height' => '250'
            ])
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [{
                            label: 'New Customers',
                            data: [{{ implode(', ', $newCustomersData ?? array_fill(0, 6, 0)) }}],
                            backgroundColor: ['rgba(79, 70, 229, 0.8)']
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            @endcomponent

            <x-card title="Top Products">
                <ul class="divide-y divide-gray-200">
                    @if(isset($topProducts) && count($topProducts) > 0)
                        @foreach($topProducts as $product)
                            <li class="py-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $product->sales_count }} sales</p>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($product->total_revenue, 2) }}</span>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="py-3 text-center text-sm text-gray-500">No product data available</li>
                    @endif
                </ul>
            </x-card>
        @endslot
    @endcomponent
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush 