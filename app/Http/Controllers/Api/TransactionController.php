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
use Illuminate\Support\Facades\DB;

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

    // Get the authenticated user's ID
    $userId = Auth::id();

    // Calculate the total revenue for today for the authenticated user
    $totalRevenue = Transaction::whereDate('created_at', $today)
        ->where('user_id', $userId) // Filter by user ID
        ->sum('total_amount');

    // Calculate the total products sold today for the authenticated user
    $totalProductsSold = TransactionItem::whereHas('transaction', function ($query) use ($today, $userId) {
            $query->whereDate('created_at', $today)
                  ->where('user_id', $userId); // Filter by user ID
        })
        ->sum('quantity');

    return response()->json([
        'total_revenue' => $totalRevenue,
        'total_products_sold' => $totalProductsSold
    ], 200);
}
public function revertTransaction(Request $request)
{
    $transactionId = $request->input('transaction_id');
    $userId = auth()->id(); // Get the authenticated user's ID

    // Fetch the transaction with its items, ensuring it belongs to the authenticated user
    $transaction = Transaction::with('transactionItems.product')
        ->where('id', $transactionId)
        ->where('user_id', $userId)
        ->first();

    if (!$transaction) {
        return response()->json(['error' => 'Transaction not found or does not belong to the authenticated user'], 404);
    }

    DB::beginTransaction();

    try {
        $revertedItems = []; // To log the reverted items

        // Restock the products
        foreach ($transaction->transactionItems as $item) {
            $product = $item->product;

            if ($product) {
                $product->stock += $item->quantity;
                $product->save();

                // Log the reverted item details
                $revertedItems[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item->quantity,
                ];
            }
        }

        // Log the reverted transaction
        DB::table('reverted_transactions')->insert([
            'user_id' => $userId,
            'transaction_id' => $transactionId,
            'transaction_items' => json_encode($revertedItems),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Delete the transaction items
        $transaction->transactionItems()->delete();

        // Delete the transaction itself
        $transaction->delete();

        DB::commit();

        return response()->json([
            'message' => 'Transaction successfully reverted',
            'reverted_transaction' => [
                'transaction_id' => $transactionId,
                'user_id' => $userId,
                'reverted_items' => $revertedItems,
            ],
        ], 200);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Failed to revert transaction: ' . $e->getMessage()], 500);
    }
}


}
