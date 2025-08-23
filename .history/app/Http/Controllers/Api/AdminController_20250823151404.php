<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.',
                    'data' => null,
                    'errors' => []
                ], 403);
            }
            return $next($request);
        });
    }

    /**
     * Get dashboard statistics
     */
    public function dashboard()
    {
        try {
            // Total statistics
            $totalUsers = User::count();
            $totalProducts = Product::count();
            $totalOrders = Order::count();
            $totalCategories = Category::count();

            // Revenue statistics
            $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');
            $monthlyRevenue = Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount');

            // Order statistics
            $pendingOrders = Order::where('status', 'pending')->count();
            $processingOrders = Order::where('status', 'processing')->count();
            $shippedOrders = Order::where('status', 'shipped')->count();
            $deliveredOrders = Order::where('status', 'delivered')->count();
            $cancelledOrders = Order::where('status', 'cancelled')->count();

            // Stock statistics
            $lowStockProducts = Product::where('stock_quantity', '<=', 10)->count();
            $outOfStockProducts = Product::where('stock_quantity', '=', 0)->count();

            // Recent orders
            $recentOrders = Order::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Top selling products
            $topProducts = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
                ->groupBy('products.id', 'products.name')
                ->orderBy('total_sold', 'desc')
                ->limit(10)
                ->get();

            // Monthly sales data (last 6 months)
            $monthlySales = Order::where('status', '!=', 'cancelled')
                ->where('created_at', '>=', now()->subMonths(6))
                ->select(
                    DB::raw('DATE_TRUNC(\'month\', created_at) as month'),
                    DB::raw('COUNT(*) as order_count'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Dashboard statistics retrieved successfully',
                'data' => [
                    'overview' => [
                        'total_users' => $totalUsers,
                        'total_products' => $totalProducts,
                        'total_orders' => $totalOrders,
                        'total_categories' => $totalCategories,
                        'total_revenue' => $totalRevenue,
                        'monthly_revenue' => $monthlyRevenue
                    ],
                    'orders' => [
                        'pending' => $pendingOrders,
                        'processing' => $processingOrders,
                        'shipped' => $shippedOrders,
                        'delivered' => $deliveredOrders,
                        'cancelled' => $cancelledOrders
                    ],
                    'inventory' => [
                        'low_stock_products' => $lowStockProducts,
                        'out_of_stock_products' => $outOfStockProducts
                    ],
                    'recent_orders' => $recentOrders,
                    'top_products' => $topProducts,
                    'monthly_sales' => $monthlySales
                ],
                'errors' => []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard statistics',
                'data' => null,
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    /**
     * Get all orders (Admin view)
     */
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by user email or order ID
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('email', 'ilike', '%' . $searchTerm . '%')
                               ->orWhere('name', 'ilike', '%' . $searchTerm . '%');
                  });
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['id', 'total_amount', 'status', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = min($request->get('limit', 20), 100);
        $orders = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Orders retrieved successfully',
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage()
            ],
            'errors' => []
        ]);
    }

    /**
     * Get all users (Admin view)
     */
    public function users(Request $request)
    {
        $query = User::withCount('orders');

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Search by name or email
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'ilike', '%' . $searchTerm . '%')
                  ->orWhere('email', 'ilike', '%' . $searchTerm . '%');
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['name', 'email', 'role', 'created_at', 'orders_count'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = min($request->get('limit', 20), 100);
        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage()
            ],
            'errors' => []
        ]);
    }

    /**
     * Get inventory report
     */
    public function inventory(Request $request)
    {
        $query = Product::with('category');

        // Filter by stock level
        if ($request->has('stock_level')) {
            switch ($request->stock_level) {
                case 'low':
                    $query->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0);
                    break;
                case 'out':
                    $query->where('stock_quantity', '=', 0);
                    break;
                case 'normal':
                    $query->where('stock_quantity', '>', 10);
                    break;
            }
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        // Sort by stock quantity
        $sortBy = $request->get('sort_by', 'stock_quantity');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['name', 'stock_quantity', 'price', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = min($request->get('limit', 20), 100);
        $products = $query->paginate($perPage);

        // Calculate inventory statistics
        $totalProducts = Product::count();
        $totalStockValue = Product::sum(DB::raw('price * stock_quantity'));
        $lowStockCount = Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count();
        $outOfStockCount = Product::where('stock_quantity', '=', 0)->count();

        return response()->json([
            'success' => true,
            'message' => 'Inventory report retrieved successfully',
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage()
            ],
            'statistics' => [
                'total_products' => $totalProducts,
                'total_stock_value' => $totalStockValue,
                'low_stock_count' => $lowStockCount,
                'out_of_stock_count' => $outOfStockCount
            ],
            'errors' => []
        ]);
    }

    /**
     * Update product stock (Admin only)
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0'
        ]);

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
                'data' => null,
                'errors' => []
            ], 404);
        }

        $oldStock = $product->stock_quantity;
        $product->update(['stock_quantity' => $request->stock_quantity]);

        // Log stock update
        \Log::info("Admin updated stock for product {$product->name}: {$oldStock} -> {$request->stock_quantity}");

        return response()->json([
            'success' => true,
            'message' => 'Product stock updated successfully',
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'old_stock' => $oldStock,
                'new_stock' => $request->stock_quantity
            ],
            'errors' => []
        ]);
    }
}
