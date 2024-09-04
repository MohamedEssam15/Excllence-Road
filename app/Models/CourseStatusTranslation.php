<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStatusTranslation extends Model
{
    use HasFactory;
    protected $table = 'courses_status_translations';
    protected $fillable = ['locale', 'name','course_status_id'];
}
