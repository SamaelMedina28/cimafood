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

        // Los pedidos cancelados no cuentan como venta
        $salesQuery = fn() => Order::whereIn('business_id', $businessIds)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Total Sales
        $totalSales = $salesQuery()->sum('total_price');

        // Orders Count
        $ordersCount = $salesQuery()->count();

        // Top Products
        $topProducts = DB::table('order_product')
            ->join('orders', 'order_product.order_id', '=', 'orders.id')
            ->join('products', 'order_product.product_id', '=', 'products.id')
            ->whereIn('orders.business_id', $businessIds)
            ->where('orders.status', '!=', 'cancelled')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('products.name', DB::raw('SUM(order_product.quantity) as total_quantity'), DB::raw('SUM(order_product.subtotal) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Top Businesses
        $topBusinesses = Business::whereIn('id', $businessIds)
            ->withSum(['orders' => function ($query) use ($startDate, $endDate) {
                $query->where('status', '!=', 'cancelled')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total_price')
            ->orderByDesc('orders_sum_total_price')
            ->limit(5)
            ->get();

        // Ventas agrupadas para la gráfica: por hora (hoy), por mes (año) o por día
        $salesSeries = $this->salesSeries($salesQuery()->get(['created_at', 'total_price']), $period, $startDate, $endDate);

        // Recent Orders
        $recentOrders = Order::whereIn('business_id', $businessIds)
            ->with(['user', 'business'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // Pedidos que esperan atención, sin importar el periodo
        $pendingCount = Order::whereIn('business_id', $businessIds)->where('status', 'pending')->count();

        // Average Order Value
        $avgOrderValue = $ordersCount > 0 ? $totalSales / $ordersCount : 0;

        return view('dashboard', compact(
            'totalSales',
            'ordersCount',
            'topProducts',
            'topBusinesses',
            'salesSeries',
            'recentOrders',
            'avgOrderValue',
            'pendingCount',
            'period',
            'startDate',
            'endDate'
        ));
    }

    private function salesSeries($orders, string $period, Carbon $startDate, Carbon $endDate): array
    {
        if ($period === 'today') {
            $buckets = collect(range(0, 23))->mapWithKeys(fn($h) => [sprintf('%02d', $h) => ['label' => sprintf('%02d:00', $h), 'total' => 0]]);
            $keyFor = fn($date) => $date->format('H');
        } elseif ($period === 'year') {
            $buckets = collect(range(1, 12))->mapWithKeys(fn($m) => [sprintf('%02d', $m) => [
                'label' => ucfirst(Carbon::create(null, $m, 1)->translatedFormat('M')),
                'total' => 0,
            ]]);
            $keyFor = fn($date) => $date->format('m');
        } else {
            $buckets = collect();
            for ($day = $startDate->copy()->startOfDay(); $day <= $endDate; $day->addDay()) {
                $buckets[$day->format('Y-m-d')] = ['label' => $day->translatedFormat('j M'), 'total' => 0];
            }
            $keyFor = fn($date) => $date->format('Y-m-d');
        }

        foreach ($orders as $order) {
            $key = $keyFor($order->created_at);
            if (isset($buckets[$key])) {
                $buckets[$key] = ['label' => $buckets[$key]['label'], 'total' => $buckets[$key]['total'] + $order->total_price];
            }
        }

        return $buckets->values()->all();
    }
}
