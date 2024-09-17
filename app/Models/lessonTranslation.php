<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonTranslation extends Model
{
    use HasFactory;
    protected $table = 'lessons_translations';
    protected $fillable = ['lesson_id','description','locale','name'];
}
