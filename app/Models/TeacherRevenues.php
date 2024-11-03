<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherRevenues extends Model
{
    use HasFactory;
    protected $fillable = ['teacher_id', 'order_id', 'revenues'];
    protected $with = ['teacher', 'order'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function scopeForCurrentMonth($query)
    {
        return $query->whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ]);
    }
}
