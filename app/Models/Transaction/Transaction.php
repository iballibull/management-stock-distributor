<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'book_transaction_id',
        'total_amount',
        'status',
        'remaining_amount',
        'amount_paid',
        'profit_amount'
    ];
}
