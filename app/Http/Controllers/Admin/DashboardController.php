<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $paidStatuses = [OrderStatus::Paid, OrderStatus::Shipped, OrderStatus::Delivered];

        return view('admin.dashboard', [
            'stats' => [
                'revenue_month' => Order::whereIn('status', $paidStatuses)
                    ->where('paid_at', '>=', now()->startOfMonth())
                    ->sum('total'),
                'orders_month' => Order::where('created_at', '>=', now()->startOfMonth())->count(),
                'to_ship' => Order::where('status', OrderStatus::Paid)->count(),
                'customers' => User::where('is_admin', false)->count(),
            ],
            'latestOrders' => Order::latest()->take(10)->get(),
            'lowStock' => Product::where('stock', '<=', 5)->orderBy('stock')->take(10)->get(),
        ]);
    }
}
