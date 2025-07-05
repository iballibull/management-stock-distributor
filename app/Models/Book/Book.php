<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;

class Book extends Model
{
    use SoftDeletes, Sortable, HasFactory;
    protected $fillable = [
        'title',
        'category_id',
        'education_level_id',
        'curriculum_id',
        'price',
        'grade_number',
        'semester',
        'image',
        'deleted_at',
        'created_at'
    ];


    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strtolower(trim($value));
    }

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
