<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLevel extends Model
{
    use HasFactory;
    protected $table = 'course_levels';
    protected $fillable = ['name'];
    protected $with=['translations'];

    public function translations()
    {
        return $this->hasMany(CourseLevelTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()->name ?? $this->name;
    }
}
