<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category', 'blog_id', 'category_id')->withTimestamps();
    }
}
