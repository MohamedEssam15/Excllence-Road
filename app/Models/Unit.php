<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = ['course_id','name'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    
    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()->name ?? $this->name;
    }

    public function translations()
    {
        return $this->hasMany(unitTranslation::class,'unit_id');
    }
}
