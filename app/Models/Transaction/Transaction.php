<?php

namespace App\Models\Transaction;

use App\Models\BookTransaction\BookTransaction;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookTransaction\TransactionType;

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

    public function bookTransaction()
    {
        return $this->belongsTo(BookTransaction::class, 'book_transaction_id');
    }
}
