<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'reciever_id', 'sender_id', 'is_read', 'course_id', 'exam_id', 'type'];
    protected $with = ['sender', 'reciever', 'course', 'exam'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function reciever()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
