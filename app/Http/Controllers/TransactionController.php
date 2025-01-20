<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Show the list of transactions for the authenticated user
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())->latest()->paginate(6); // Only show transactions for the authenticated user
        return view('transactions.index', compact('transactions'));
    }

    // Store a new transaction for the authenticated user
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Create a new transaction and associate it with the authenticated user
        $transaction = Transaction::create([
            'total_amount' => $request->total_amount,
            'user_id' => Auth::id(), // Associate the transaction with the authenticated user
        ]);

        // Redirect to the transactions list with a success message
        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully!');
    }
}

