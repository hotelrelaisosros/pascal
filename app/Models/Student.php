<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\ImageHelper;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'address', 'image'];
    // public function getImageAttribute($value)
    // {
    //     return ImageHelper::formatImageUrl($value);
    // }
}
