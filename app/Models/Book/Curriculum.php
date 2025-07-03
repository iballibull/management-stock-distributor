<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Curriculum extends Model
{
    use SoftDeletes, Sortable;
    protected $fillable = ['name', 'created_at'];

    protected $table = 'curriculums';
    public $sortable = ['name'];
}
