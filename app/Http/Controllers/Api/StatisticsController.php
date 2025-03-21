<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function dailyStats(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        $userId = auth()->id(); // Get the ID of the authenticated user

        // Total Sales of the Day for the authenticated user
        $totalSales = Transaction::where('user_id', $userId)
            ->whereDate('created_at', $date)
            ->sum('total_amount');

        // Total Transactions Made in a Day for the authenticated user
        $totalTransactions = Transaction::where('user_id', $userId)
            ->whereDate('created_at', $date)
            ->count();

        // Most Sold Product of the Day for the authenticated user
        $mostSoldProduct = TransactionItem::select('product_id', \DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('transaction', function ($query) use ($userId, $date) {
                $query->where('user_id', $userId)
                    ->whereDate('created_at', $date);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'DESC')
            ->first();

        $mostSoldProductDetails = null;
        if ($mostSoldProduct) {
            $product = Product::find($mostSoldProduct->product_id);
            $mostSoldProductDetails = [
                'product_name' => $product->name,
                'total_quantity' => $mostSoldProduct->total_quantity,
            ];
        }

        // Total Products Sold by the authenticated user
        $totalProductsSold = TransactionItem::whereHas('transaction', function ($query) use ($userId, $date) {
            $query->where('user_id', $userId)
                ->whereDate('created_at', $date);
        })->sum('quantity');

        // Transactions done by the authenticated user on the given day
        $transactions = Transaction::where('user_id', $userId)
            ->whereDate('created_at', $date)
            ->with(['user', 'items.product']) // Include user and product data
            ->get();

        // Prepare transactions data
        $transactionDetails = $transactions->map(function ($transaction) {
            return [
                'transaction_id' => $transaction->id,
                'total_amount' => $transaction->total_amount,
                'timestamp' => $transaction->created_at,
                'user_name' => $transaction->user ? $transaction->user->name : null,
                'items' => $transaction->items->map(function ($item) {
                    return [
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->subtotal,
                    ];
                }),
            ];
        });

        return response()->json([
            'date' => $date,
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
            'most_sold_product' => $mostSoldProductDetails,
            'total_products_sold' => $totalProductsSold,
            'transactions' => $transactionDetails,
        ]);
    }
}
