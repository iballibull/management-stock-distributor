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
        'return_percentage',
        'max_return_quantity',
        'remaining_return_quantity',
        'created_at',
        'updated_at',
    ];
}
