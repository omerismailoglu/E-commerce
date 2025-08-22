<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends ApiController
{
    // get
    public function dashboard(Request $request)
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'total_orders' => Order::count(),
                'total_products' => Product::count(),
                'total_categories' => Category::count(),
                'total_revenue' => Order::where('status', 'confirmed')->sum('total_amount'),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'low_stock_products' => Product::where('stock_quantity', '<', 10)->count(),
            ];

            $recent_orders = Order::with(['user', 'items.product'])
                ->latest()
                ->take(5)
                ->get();

            $top_products = Product::with('category')
                ->orderBy('stock_quantity', 'asc')
                ->take(5)
                ->get();

            Log::info('Admin dashboard accessed', ['admin_user_id' => $request->user()->id]);

            return $this->successResponse([
                'stats' => $stats,
                'recent_orders' => $recent_orders,
                'top_products' => $top_products,
            ]);
        } catch (\Exception $e) {
            Log::error('Admin dashboard error', [
                'admin_user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);
            return $this->errorResponse('Dashboard yüklenirken hata oluştu', [], 500);
        }
    }

    // get
    public function userStats(Request $request)
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'admin_users' => User::where('role', 'admin')->count(),
                'regular_users' => User::where('role', 'user')->count(),
                'users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ];

            $recent_users = User::latest()->take(10)->get(['id', 'name', 'email', 'role', 'created_at']);

            Log::info('User stats accessed', ['admin_user_id' => $request->user()->id]);

            return $this->successResponse([
                'stats' => $stats,
                'recent_users' => $recent_users,
            ]);
        } catch (\Exception $e) {
            Log::error('User stats error', [
                'admin_user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);
            return $this->errorResponse('Kullanıcı istatistikleri yüklenirken hata oluştu', [], 500);
        }
    }

    // get
    public function orderStats(Request $request)
    {
        try {
            $stats = [
                'total_orders' => Order::count(),
                'confirmed_orders' => Order::where('status', 'confirmed')->count(),
                'pending_orders' => Order::where('status', 'pending')->count(),
                'cancelled_orders' => Order::where('status', 'cancelled')->count(),
                'total_revenue' => Order::where('status', 'confirmed')->sum('total_amount'),
                'avg_order_value' => Order::where('status', 'confirmed')->avg('total_amount'),
            ];

            $monthly_orders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count, SUM(total_amount) as revenue')
                ->whereYear('created_at', now()->year)
                ->groupBy('month')
                ->get();

            Log::info('Order stats accessed', ['admin_user_id' => $request->user()->id]);

            return $this->successResponse([
                'stats' => $stats,
                'monthly_orders' => $monthly_orders,
            ]);
        } catch (\Exception $e) {
            Log::error('Order stats error', [
                'admin_user_id' => $request->user()->id,
                'error' => $e->getMessage()
            ]);
            return $this->errorResponse('Sipariş istatistikleri yüklenirken hata oluştu', [], 500);
        }
    }
}
