<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'amount', 'user_id', 'order_id'];
    protected $with = ['translations'];

    public function translations()
    {
        return $this->hasMany(PaymentTranslation::class, 'payment_id', 'id');
    }

    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()->status ?? $this->status;
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function enrollment()
    {
        return $this->belongsToMany(Course::class, 'courses_users', 'payment_id', 'course_id')->withPivot('user_id', 'start_date', 'end_date', 'from_package', 'package_id')
            ->withTimestamps();
    }
}
