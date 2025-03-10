<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded  = ['id'];

    protected $casts = [
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];
    
    public function blog(){
        return $this->hasMany(Blog::class);
    }

}
