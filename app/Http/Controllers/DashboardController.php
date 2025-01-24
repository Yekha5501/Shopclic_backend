<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{


public function index()
{
    $userId = Auth::id();

    // Total Stock Worth (only for products belonging to the authenticated user)
    $totalStockWorth = Product::where('user_id', $userId)
        ->sum(DB::raw('price * stock'));

    // Revenue for the day (for transactions belonging to the authenticated user)
    $today = Carbon::today();
    $revenueToday = Transaction::where('user_id', $userId)
        ->whereDate('created_at', $today)
        ->sum('total_amount');

    // Total Transactions for Today (filtered by user)
    $totalTransactionsToday = Transaction::where('user_id', $userId)
        ->whereDate('created_at', $today)
        ->count();

    // Top-Selling Products (filtered by user's transactions)
    $topProducts = TransactionItem::select(
        'product_id',
        DB::raw('SUM(quantity) as total_quantity'),
        DB::raw('SUM(subtotal) as total_revenue')
    )
        ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
        ->where('transactions.user_id', $userId)
        ->groupBy('product_id')
        ->orderBy('total_quantity', 'desc')
        ->take(5)
        ->with('product:id,name,price') // Load product details
        ->get();

    // Product Stock Levels (filtered by user and limited to 5 products)
    $products = Product::where('user_id', $userId)
        ->take(5)
        ->get();

    // Sales Trend for Last 7 Days (filtered by user's transactions)
    $salesTrends = Transaction::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total_amount) as total_sales')
    )
        ->where('user_id', $userId)
        ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Calculate stock worth trends
    $stockWorthTrends = collect();
    $previousStockData = Product::where('user_id', $userId)->pluck('stock', 'id'); // Filter by user

    foreach ($salesTrends as $salesTrend) {
        $date = $salesTrend->date;

        // Get sales transactions for the specific day (filtered by user)
        $sales = TransactionItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_quantity')
        )
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transactions.user_id', $userId)
            ->whereDate('transactions.created_at', $date)
            ->groupBy('product_id')
            ->get();

        // Subtract the sold stock from the initial stock
        $dailyStockWorth = 0;
        foreach ($sales as $sale) {
            $productId = $sale->product_id;
            $quantitySold = $sale->total_quantity;

            // Get the price and stock for this product (filtered by user)
            $product = Product::where('id', $productId)
                ->where('user_id', $userId)
                ->first();

            if ($product) {
                $initialStock = $previousStockData[$productId];
                $remainingStock = $initialStock - $quantitySold;

                // Calculate stock worth for the day
                $dailyStockWorth += $product->price * $remainingStock;

                // Update the stock for the next day
                $previousStockData[$productId] = $remainingStock;
            }
        }

        $stockWorthTrends->push([
            'date' => $date,
            'total_stock_worth' => $dailyStockWorth
        ]);
    }

    // Prepare the chart data
    $chartData = [
        'dates' => $salesTrends->pluck('date')->map(fn($date) => Carbon::parse($date)->format('M d')),
        'sales' => $salesTrends->pluck('total_sales'),
        'stockWorth' => $stockWorthTrends->pluck('total_stock_worth'),
    ];

    return view('dashboard', compact(
        'totalStockWorth',
        'revenueToday',
        'totalTransactionsToday',
        'topProducts',
        'products',
        'chartData'
    ));
}

}
