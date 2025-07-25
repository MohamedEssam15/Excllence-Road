<?php

namespace App\Models;

use App\Utilities\FilterBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['question', 'category_id', 'answer_id', 'user_id', 'is_question_bank', 'question_type'];

    public function answer()
    {
        return $this->belongsTo(QuestionAnswer::class, 'answer_id');
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class, 'question_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(category::class, 'category_id');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exams_questions', 'question_id', 'exam_id');
    }

    public function examsNotCreatedByTeacher()
    {
        $teacherId = $this->user_id;
        return $this->exams()
            ->whereHas('courses', function ($query) use ($teacherId) {
                $query->where('teacher_id', '!=', $teacherId);
            })
            ->exists();
    }

    public function userQuestiions()
    {
        return $this->belongsToMany(User::class, 'users_questions', 'question_id', 'user_id')->withPivot('is_correct')->withTimestamps();
    }

    public function getQuestion()
    {
        if ($this->question_type != 'text') {
            return Storage::disk('public')->url('teacher_questions/' . $this->user_id . '/' . $this->id . '/' . $this->question);
        } else {
            return $this->question;
        }
    }

    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\QuestionFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}
