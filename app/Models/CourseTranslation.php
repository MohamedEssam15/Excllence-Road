<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTranslation extends Model
{
    use HasFactory;
    protected $table = 'courses_translations';
    protected $fillable = ['course_id','locale','name','description','specific_to'];
}
