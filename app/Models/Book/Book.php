<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Book extends Model
{
    use SoftDeletes, Sortable, HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }
}
