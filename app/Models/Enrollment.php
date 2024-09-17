<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','enrollable_type','enrollable_id','payment_id'];

    public function enrollable()
    {
        return $this->morphTo();
    }

    public function student()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
