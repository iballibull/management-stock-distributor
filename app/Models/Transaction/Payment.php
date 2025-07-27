<?php

namespace App\Models\Transaction;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount',
        'status',
        'payment_date',
        'payment_method',
        'notes',
        'validate_by'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validate_by')->withTrashed();
    }
}


