<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Package extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','price','cover_photo','start_date','end_date','is_popular'];
    protected $with=['translations'];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class,'courses_packages','package_id','course_id');
    }

    public function enrollments()
    {
        return $this->morphMany(Enrollment::class, 'enrollable');
    }

    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first() ?? $this;
    }

    public function translations()
    {
        return $this->hasMany(PackageTranslation::class,'package_id');
    }
    public function getCoverPhotoPath(){
        return asset('packages_attachments/'.$this->id.'/cover_photo/'.$this->cover_photo);
    }
}
