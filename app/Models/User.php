<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_active',
        'is_blocked',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function attachments()
    {
        return $this->hasMany(UserAttchment::class, 'user_id');
    }

    public function getAvatarPath()
    {
        return asset('users_attachments/' . $this->id . '/avatar/' . $this->avatar);
    }

    public function teacherCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }


    public function studentExams()
    {
        return $this->belongsToMany(Exam::class, 'exams_users', 'user_id', 'exam_id')->withPivot('file_name', 'grade', 'degree', 'start_time', 'course_id')->withTimestamps();
    }
    public function studentQuestions()
    {
        return $this->belongsToMany(Question::class, 'users_questions', 'user_id', 'question_id')->withPivot('is_correct', 'exam_id', 'course_id', 'answer_id')->withTimestamps();
    }
    public function enrollments()
    {
        return $this->belongsToMany(Course::class, 'courses_users', 'user_id', 'course_id', 'id', 'id')->withPivot('payment_id', 'start_date', 'end_date', 'from_package', 'package_id')
            ->withTimestamps();
    }

    public function teacherRevenues()
    {
        return $this->hasMany(TeacherRevenues::class, 'teacher_id');
    }
    public function teacherQuestionsBank()
    {
        return $this->belongsToMany(Question::class, 'teacher_questions_bank', 'teacher_id', 'question_id')->withTimestamps();
    }

    public function currentMonthRevenue()
    {
        return $this->teacherRevenues()
            ->forCurrentMonth()
            ->sum('revenues');
    }
    public function currentMonthSoldCourses()
    {
        return $this->teacherRevenues()
            ->forCurrentMonth()
            ->count();
    }

    public function totalRevenue()
    {
        return $this->teacherRevenues()->sum('revenues');
    }

    public function monthlyRevenue()
    {
        return $this->teacherRevenues()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(revenues) as total_revenue')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
    }



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
