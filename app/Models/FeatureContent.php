<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'cover_photo',
        'cover_video',
        'course_id',
        'package_id',
        'modelable_type',
        'type'
    ];

    public function modelable()
    {
        if ($this->modelable_type == 'course') {
            return $this->belongsTo(Course::class, 'course_id');
        } elseif ($this->modelable_type == 'package') {
            return $this->belongsTo(Package::class, 'package_id');
        }
    }

    public function getCoverPath()
    {
        if ($this->type == 'photo') {
            return asset('feature_content/' . $this->id . '/cover_photo/' . $this->cover_photo);
        } elseif ($this->type == 'video') {
            return asset('feature_content/' . $this->id . '/cover_video/' . $this->cover_video);
        }
    }
}
