<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;
    protected $fillable = ['content_id','type'];
    // protected $with = ['content'];

    public function content()
    {
        if($this->type == 'new-course'){
            return $this->belongsTo(Course::class,'content_id');
        }elseif($this->type == 'new-teacher'){
            return $this->belongsTo(User::class,'content_id');
        }elseif($this->type == 'new-contact-us-message'){
            return $this->belongsTo(ContactUsMessage::class,'content_id');
        }
    }
}
