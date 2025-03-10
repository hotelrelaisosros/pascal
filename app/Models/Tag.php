<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];
    

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_tag', 'tag_id', 'blog_id');
    }
}
