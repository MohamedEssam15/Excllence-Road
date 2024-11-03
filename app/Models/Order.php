<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['order_number', 'student_id', 'is_package', 'package_id', 'course_id', 'payment_id'];
    protected $with = ['student', 'payment'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function product()
    {
        return $this->is_package ? $this->belongsTo(Package::class, 'package_id') : $this->belongsTo(Course::class, 'course_id');
    }


    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
