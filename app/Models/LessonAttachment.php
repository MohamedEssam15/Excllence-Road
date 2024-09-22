<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LessonAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['lesson_id','name'];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class,'lesson_id');
    }

    public function getAttchment(){
        return Storage::disk('public')->url('lessons/lessons_attachments/'.$this->lesson_id.'/'. $this->name);
    }
}
