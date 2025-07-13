<?php

namespace App\Models\BookTransaction;

use App\Models\Book\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
