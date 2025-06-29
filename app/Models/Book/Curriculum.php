<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curriculum extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'created_at'];

    protected $table = 'curriculums';
}
