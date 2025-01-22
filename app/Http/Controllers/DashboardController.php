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

    // Total Stock Worth
    $totalStockWorth = Product::sum(DB::raw('price * stock'));

    // Revenue for the day
    $today = Carbon::today();
    $revenueToday = Transaction::where('user_id', $userId)
        ->whereDate('created_at', $today)
        ->sum('total_amount');

    // Total Transactions for Today
    $totalTransactionsToday = Transaction::where('user_id', $userId)
        ->whereDate('created_at', $today)
        ->count();

    // Top-Selling Products
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
        ->with('product:id,name,price')
        ->get();

    // Product Stock Levels
    $products = Product::take(5)->get();

    // Sales Trend for Last 7 Days
    $salesTrends = Transaction::select(
        DB::raw('DATE(created_at) as date'),
        DB::raw('SUM(total_amount) as total_sales')
    )
        ->where('user_id', $userId)
        ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    // Calculate stock worth for each day after sales
    $stockWorthTrends = collect();
    $previousStockData = Product::pluck('stock', 'id'); // Get the initial stock levels

    foreach ($salesTrends as $salesTrend) {
        $date = $salesTrend->date;

        // Get sales transactions for the specific day
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

            // Get the price and stock for this product
            $product = Product::find($productId);
            $initialStock = $previousStockData[$productId];
            $remainingStock = $initialStock - $quantitySold;

            // Calculate stock worth for the day
            $dailyStockWorth += $product->price * $remainingStock;

            // Update the stock for the next day
            $previousStockData[$productId] = $remainingStock;
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
