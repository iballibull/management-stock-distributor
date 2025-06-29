<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EducationLevel extends Model
{
    use SoftDeletes;
    protected $fillable = ['name'];
}
