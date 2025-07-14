<?php

namespace App\Models\Book;

use App\Models\BookStock\BookStockBatch;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class)->withTrashed();
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class)->withTrashed();
    }

    public function bookStockBatches()
    {
        return $this->hasMany(BookStockBatch::class);
    }

    public function getFormattedTitleAttribute()
    {
        return Str::title($this->title) . ' - ' .
            $this->category->name . ' - ' .
            $this->educationLevel->name . ' Kelas ' .
            $this->grade_number . ' - ' .
            $this->curriculum->name . ' - ' . 'Semester ' .
            $this->semester;
    }
}
