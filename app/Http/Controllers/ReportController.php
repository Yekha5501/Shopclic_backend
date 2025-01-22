<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;

class ReportController extends Controller
{
   
public function index()
{
    $userId = Auth::id(); // Authenticated user ID

    // Top Selling Products
    $topProducts = TransactionItem::select('product_id', Product::raw('SUM(quantity) as total_quantity'), Product::raw('SUM(subtotal) as total_revenue'))
        ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
        ->where('transactions.user_id', $userId)
        ->groupBy('product_id')
        ->orderBy('total_quantity', 'desc')
        ->take(5)
        ->with('product:id,name,price')
        ->get();

    // Transactions Grouped by Date
    $transactionsByDate = Transaction::where('user_id', $userId)
        ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
        ->groupBy('date')
        ->orderByDesc('date')
        ->paginate(10);

    // Format date to ensure proper routing
    $transactionsByDateFormatted = $transactionsByDate->map(function ($transaction) {
        $transaction->date = Carbon::parse($transaction->date)->format('Y-m-d'); // Ensure the format is 'Y-m-d'
        return $transaction;
    });

    return view('reports.index', compact('topProducts', 'transactionsByDateFormatted'));
}

// Detailed Transactions for a Date
public function transactionsByDate($date)
{
    $userId = Auth::id();
    // Ensure the $date format is valid
    $formattedDate = Carbon::parse($date)->format('Y-m-d');

    $transactions = Transaction::where('user_id', $userId)
        ->whereDate('created_at', $formattedDate)
        ->with('transactionItems.product')
        ->get();

    return view('reports.transactions', compact('transactions', 'formattedDate'));
}

// Detailed Transactions for a Date






 //
}



