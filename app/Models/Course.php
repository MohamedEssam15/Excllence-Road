<?php

namespace App\Models;

use App\Utilities\FilterBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'name', 'teacher_commision', 'teacher_id', 'category_id', 'start_date', 'end_date', 'is_specific', 'specific_to', 'status_id', 'price', 'level_id', 'cover_photo_name', 'rating', 'course_trailer', 'discount', 'discount_type', 'new_price'];
    protected $with = ['translations', 'level', 'status', 'category', 'teacher'];


    public function units(): HasMany
    {
        return $this->hasMany(Unit::class)->orderBy('order', 'asc');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(CourseStatus::class);
    }
    public function level(): BelongsTo
    {
        return $this->belongsTo(CourseLevel::class);
    }

    public function getHaveOrdersAttribute(){
        return $this->orders()->exists();
    }



    public function category(): BelongsTo
    {
        return $this->belongsTo(category::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function translations()
    {
        return $this->hasMany(CourseTranslation::class, 'course_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'course_id');
    }
    public function getAverageRatingAttribute()
    {
        return (int) $this->reviews()->avg('rating');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'courses_exams', 'course_id', 'exam_id')->withPivot('available_from', 'available_to', 'id')->withTimestamps();
    }

    public function exam($examId)
    {
        return $this->exams()->where('exam_id', $examId)->first();
    }

    public function getCoverPhotoPath()
    {
        return asset('course_attachments/' . $this->id . '/cover_photo/' . $this->cover_photo_name);
    }
    public function getCourseTrailerPath()
    {
        if ($this->course_trailer != null) {
            return asset('course_attachments/' . $this->id . '/trailer/' . $this->course_trailer);
        } else {
            return null;
        }
        return asset('course_attachments/' . $this->id . '/trailer/' . $this->course_trailer);
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'course_id');
    }
    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\CourseFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }

    public function enrollments()
    {
        return $this->belongsToMany(User::class, 'courses_users')->withPivot('payment_id', 'start_date', 'end_date', 'from_package', 'package_id')
            ->withTimestamps();
    }
}
