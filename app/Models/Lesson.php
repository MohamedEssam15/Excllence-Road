<?php

namespace App\Models;

use App\Services\VideoServices\VideoStorageManager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['description', 'name', 'type', 'video_link', 'unit_id', 'order', 'meeting_date'];
    protected $with = ['translations'];

    protected $casts = [
        'meeting_date' => 'datetime'
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(LessonAttachment::class);
    }

    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function translations()
    {
        return $this->hasMany(LessonTranslation::class, 'lesson_id');
    }

    public function getVideoLink()
    {
        if ($this->type == 'video') {
            $videoService = new VideoStorageManager();
            return $videoService->retrieveVideo($this->video_link, $this->id);
        } else {
            return $this->video_link;
        }
    }
}
