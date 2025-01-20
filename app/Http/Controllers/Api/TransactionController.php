<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Events\ProductUpdated;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Record a transaction and update product stock.
     */
    

public function store(Request $request)
{
    // Validate the request data
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    try {
        $totalAmount = 0;
        $transactionItems = [];

        // Start a database transaction
        \DB::beginTransaction();

        // Process each item in the request
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);

            // Check stock availability
            if ($product->stock < $item['quantity']) {
                \DB::rollBack(); // Rollback transaction
                return response()->json([
                    'error' => "Insufficient stock for product ID {$product->id}"
                ], 400);
            }

            // Deduct stock
            $product->stock -= $item['quantity'];
            $product->save();

            // Trigger the ProductUpdated event
            event(new ProductUpdated($product));

            // Calculate total amount
            $subtotal = $product->price * $item['quantity'];
            $totalAmount += $subtotal;

            // Prepare data for the pivot table (transaction_items)
            $transactionItems[] = [
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Create a new transaction record with user_id
        $transaction = Transaction::create([
            'total_amount' => $totalAmount,
            'timestamp' => now(),
             'user_id' => auth()->id(), // Assuming the user is authenticated
        ]);

        // Insert the items into the pivot table
        $transaction->products()->attach($transactionItems);

        // Commit the database transaction
        \DB::commit();

        // Return success response
        return response()->json([
            'message' => 'Transaction recorded successfully',
            'transaction_id' => $transaction->id,
            'total_amount' => $totalAmount,
        ], 201);
    } catch (\Exception $e) {
        // Rollback the transaction in case of error
        \DB::rollBack();

        // Log the error for debugging
        \Log::error('Transaction error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        // Return the error response with the actual message
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
}




    public function dailySalesSummary(Request $request)
    {
        // Get today's date
        $today = Carbon::today();

        // Calculate the total revenue for today
        $totalRevenue = Transaction::whereDate('created_at', $today)
            ->sum('total_amount');

        // Calculate the total products sold today
        $totalProductsSold = TransactionItem::whereHas('transaction', function ($query) use ($today) {
            $query->whereDate('created_at', $today);
        })
            ->sum('quantity');

        return response()->json([
            'total_revenue' => $totalRevenue,
            'total_products_sold' => $totalProductsSold
        ], 200);
    }
}
