<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLevelTranslation extends Model
{
    use HasFactory;
    protected $table = 'course_level_translations';
    protected $fillable = ['locale', 'name','course_level_id'];
}
