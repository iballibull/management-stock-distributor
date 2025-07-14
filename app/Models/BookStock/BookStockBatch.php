<?php

namespace App\Models\BookStock;

use Illuminate\Database\Eloquent\Model;

class BookStockBatch extends Model
{
    protected $fillable = [
        'book_id',
        'semester_id',
        'purchase_price',
        'quantity',
        'remaining_quantity',
        'mutation_percentage',
        'return_percentage',
        'max_return_quantity',
        'max_mutation_quantity',
        'remaining_return_quantity',
        'remaining_mutation_quantity',
        'created_at',
        'updated_at',
    ];
}
