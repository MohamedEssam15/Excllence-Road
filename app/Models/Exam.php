<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_unit_exam', 'type', 'file_name', 'exam_time', 'degree'];

    public function studentsAnswer()
    {
        return $this->belongsToMany(User::class, 'exams_users', 'exam_id', 'user_id')->withPivot('file_name', 'grade', 'degree', 'start_time', 'course_id')
            ->withTimestamps();
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'exams_questions', 'exam_id', 'question_id');
    }

    public function units()
    {
        return $this->belongsToMany(Unit::class, 'exams_units', 'exam_id', 'unit_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'courses_exams', 'exam_id', 'course_id')->withPivot('available_from', 'available_to')->withTimestamps();
    }
    public function course($courseId)
    {
        return $this->courses()->where('course_id', $courseId)->first();
    }

    public function getExamFile()
    {
        if ($this->type != 'mcq') {
            return Storage::disk('public')->url("exams/{$this->id}/" . $this->file_name);
        } else {
            return null;
        }
    }
    public function getExamDegree()
    {
        if ($this->type != 'mcq') {
            return $this->degree;
        } else {
            return $this->questions->count();
        }
    }
}
