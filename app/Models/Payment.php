<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['status','amount','user_id'];
    protected $with=['translations'];

    public function translations()
    {
        return $this->hasMany(PaymentTranslation::class,'payment_id','id');
    }

    public function translate($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first()->status ?? $this->status;
    }

    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }
}
