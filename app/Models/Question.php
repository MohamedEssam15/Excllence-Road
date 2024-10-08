<?php

namespace App\Models;

use App\Utilities\FilterBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = ['question', 'category_id', 'answer_id', 'user_id', 'is_question_bank'];

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
        return $this->belongsToMany(Exam::class, 'exams_questions', 'exam_id', 'question_id');
    }

    public function userQuestiions()
    {
        return $this->belongsToMany(User::class, 'users_questions', 'question_id', 'user_id')->withPivot('is_correct')->withTimestamps();
    }

    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Utilities\QuestionFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}
