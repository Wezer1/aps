<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryImage extends Model
{
    use HasFactory;

    protected $table = 'category_images';
    protected $guarded = false;
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
