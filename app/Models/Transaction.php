<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'total_amount',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'transaction_items')
            ->withPivot('quantity', 'subtotal')
            ->withTimestamps();
    }

     public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
