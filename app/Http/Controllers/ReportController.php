<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\RevertedTransaction;

class ReportController extends Controller
{
  public function index(Request $request)
{
    $userId = Auth::id(); // Authenticated user ID

    // Products Out of Stock
    $outOfStockProducts = Product::where('user_id', $userId)
        ->where('stock', '<=', 0) // Products with stock <= 0
        ->orderBy('stock', 'asc') // Prioritize products with the lowest stock
        ->take(5) // Limit to 5 products
        ->get();

    // Products Close to Finishing (e.g., stock <= 5)
    $lowStockProducts = Product::where('user_id', $userId)
        ->where('stock', '>', 0) // Products with stock > 0
        ->where('stock', '<=', 5) // Products with stock <= 5
        ->orderBy('stock', 'asc') // Prioritize products with the lowest stock
        ->take(5) // Limit to 5 products
        ->get();

    // Date search for Transactions Table
    $transactionDate = $request->query('transaction_date');

    // Transactions Grouped by Date
    $transactionsByDate = Transaction::where('user_id', $userId)
        ->when($transactionDate, function ($query, $transactionDate) {
            return $query->whereDate('created_at', $transactionDate);
        })
        ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
        ->groupBy('date')
        ->orderByDesc('date')
        ->paginate(2);

    $transactionsByDateFormatted = $transactionsByDate->map(function ($transaction) {
        $transaction->date = Carbon::parse($transaction->date)->format('Y-m-d');
        return $transaction;
    });

    // Search term for Reverted Transactions Table
    $revertedTransactionSearch = $request->query('reverted_transaction_search');

    // Fetch Reverted Transactions (Paginated)
    $revertedTransactions = RevertedTransaction::with(['user', 'transaction'])
        ->where('user_id', $userId)
        ->when($revertedTransactionSearch, function ($query, $revertedTransactionSearch) {
            return $query->where('id', 'like', "%{$revertedTransactionSearch}%")
                         ->orWhere('transaction_id', 'like', "%{$revertedTransactionSearch}%")
                         ->orWhereHas('user', function ($q) use ($revertedTransactionSearch) {
                             $q->where('name', 'like', "%{$revertedTransactionSearch}%");
                         });
        })
        ->paginate(2); // Paginate with 10 items per page

    return view('reports.index', compact(
        'outOfStockProducts',
        'lowStockProducts',
        'transactionsByDate',
        'transactionsByDateFormatted',
        'revertedTransactions',
        'transactionDate',
        'revertedTransactionSearch'
    ));
}
// Detailed Transactions for a Date
public function transactionsByDate(Request $request, $date)
{
    $userId = Auth::id();
    // Ensure the $date format is valid
    $formattedDate = Carbon::parse($date)->format('Y-m-d');

    // Get the search term from the request
    $search = $request->query('search');

    // Fetch Transactions with Search Filter
    $transactions = Transaction::where('user_id', $userId)
        ->whereDate('created_at', $formattedDate)
        ->when($search, function ($query, $search) {
            return $query->where('id', 'like', "%{$search}%")
                         ->orWhereHas('transactionItems.product', function ($q) use ($search) {
                             $q->where('name', 'like', "%{$search}%");
                         });
        })
        ->with('transactionItems.product')
        ->paginate(3);

    return view('reports.transactions', compact('transactions', 'formattedDate', 'search'));
}

// Detailed Transactions for a Date






 //
}



