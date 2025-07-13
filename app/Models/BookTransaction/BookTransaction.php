<?php

namespace App\Models\BookTransaction;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookTransaction\Semester;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kyslik\ColumnSortable\Sortable;

class BookTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\BookTransactionFactory> */
    use HasFactory, Sortable;
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

    public $sortable = [
        'total_quantity',
        'created_at',
        'approved_at',
        'user.name',
        'semester.name',
        'approvedBy.name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class)->withTrashed();
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by')->withTrashed();
    }
}
