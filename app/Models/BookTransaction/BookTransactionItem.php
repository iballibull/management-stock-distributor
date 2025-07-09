<?php

namespace App\Models\BookTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookTransactionItem extends Model
{
    /** @use HasFactory<\Database\Factories\BookTransaction\BookTransactionItemFactory> */
    use HasFactory;

    protected $fillable = [
        'book_stock_batch_id',
        'book_transaction_id',
        'book_id',
        'quantity',
        'unit_price',
        'total_price',
        'mutation_percentage',
        'return_percentage',
        'mutation_percentage',
        'created_at',
        'updated_at',
    ];
}
