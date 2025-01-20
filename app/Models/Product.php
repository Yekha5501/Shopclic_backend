<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'stock',
        'user_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2', // Ensure price is always cast as a decimal with 2 decimal places
        'stock' => 'integer',   // Ensure stock is treated as an integer
    ];

    /**
     * Define the relationship with the TransactionItem model.
     * A product can be associated with many transaction items.
     */
    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Define the relationship with the Transaction model through TransactionItem.
     * A product can have many transactions through its items.
     */
    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transaction_items')
                    ->withPivot('quantity', 'subtotal') // Include the additional fields in the pivot table
                    ->withTimestamps();
    }

    /**
     * Calculate the total value of a product's stock based on its price.
     *
     * @return float
     */
    public function totalStockValue()
    {
        return $this->price * $this->stock;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
