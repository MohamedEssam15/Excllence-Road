<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStatus extends Model
{
    use HasFactory;

    protected $table = 'courses_statuses';
    protected $fillable = ['name'];
    protected $with=['translations'];

    public function translations()
    {
        return $this->hasMany(CourseStatusTranslation::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()->name ?? $this->name;
    }
}
