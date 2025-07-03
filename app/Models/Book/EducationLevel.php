<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class EducationLevel extends Model
{
    use SoftDeletes, Sortable;
    protected $fillable = ['name'];
    public $sortable = ['name'];
}
