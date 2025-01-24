<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevertedTransaction extends Model
{
    use HasFactory;

    // Table name (optional if following Laravel naming conventions)
    protected $table = 'reverted_transactions';

    // Mass-assignable attributes
    protected $fillable = [
        'user_id',
        'transaction_id',
        'transaction_items',
    ];

    // Cast 'transaction_items' to array for easier handling
    protected $casts = [
        'transaction_items' => 'array',
    ];

    /**
     * Define the relationship to the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship to the Transaction model.
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
