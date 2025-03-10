<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
        'additional_images' => 'array',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
