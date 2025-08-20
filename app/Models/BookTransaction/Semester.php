<?php

namespace App\Models\BookTransaction;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use SoftDeletes, Sortable;

    protected $fillable = [
        'name',
        'year',
        'semester_number',
        'start_date',
        'end_date',
    ];


    protected $sortable = [
        'name',
        'semester_number',
        'start_date',
        'end_date',
    ];
}
