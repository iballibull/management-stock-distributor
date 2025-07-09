<?php

namespace App\Models\BookTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\BookTransactionFactory> */
    use HasFactory;


    protected $fillable = [
        'user_id',
        'semester_id',
        'transaction_type_id',
        'total_quantity',
        'total_value',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];
}
