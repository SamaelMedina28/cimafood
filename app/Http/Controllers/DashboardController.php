<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->is_vendor) {
            return redirect()->route('store');
        }

        $period = $request->input('period', 'month'); // month, week, year, custom
        $startDate = Carbon::now();
        $endDate = Carbon::now();

        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                break;
            case 'today':
                $startDate = Carbon::now()->startOfDay();
                break;
            case 'month':
            default:
                $startDate = Carbon::now()->startOfMonth();
                break;
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
            $period = 'custom';
        }

        $businessIds = $user->businesses()->pluck('id');

        // Total Sales
        $totalSales = Order::whereIn('business_id', $businessIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        // Orders Count
        $ordersCount = Order::whereIn('business_id', $businessIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Top Products
        $topProducts = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->whereIn('orders.business_id', $businessIds)
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('products.name', DB::raw('SUM(order_product.quantity) as total_quantity'), DB::raw('SUM(order_product.subtotal) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Top Businesses
        $topBusinesses = Business::whereIn('id', $businessIds)
            ->withSum(['orders' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total_price')
            ->orderByDesc('orders_sum_total_price')
            ->limit(5)
            ->get();

        // Sales by day (for a chart)
        $salesByDay = Order::whereIn('business_id', $businessIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Recent Orders
        $recentOrders = Order::whereIn('business_id', $businessIds)
            ->with(['user', 'business'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // Average Order Value
        $avgOrderValue = $ordersCount > 0 ? $totalSales / $ordersCount : 0;

        return view('dashboard', compact(
            'totalSales',
            'ordersCount',
            'topProducts',
            'topBusinesses',
            'salesByDay',
            'recentOrders',
            'avgOrderValue',
            'period',
            'startDate',
            'endDate'
        ));
    }
}
