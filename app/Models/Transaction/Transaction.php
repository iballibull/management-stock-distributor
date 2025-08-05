<?php

namespace App\Models\Transaction;

use App\Models\User\User;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookTransaction\BookTransaction;
use App\Models\BookTransaction\TransactionType;

class Transaction extends Model
{
    use Sortable;
    protected $fillable = [
        'user_id',
        'book_transaction_id',
        'total_amount',
        'status',
        'remaining_amount',
        'amount_paid',
        'profit_amount'
    ];

    protected $sortable = [
        'total_amount',
        'remaining_amount',
        'amount_paid',
        'created_at',
    ];

    public function bookTransaction()
    {
        return $this->belongsTo(BookTransaction::class, 'book_transaction_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'transaction_id');
    }
}
