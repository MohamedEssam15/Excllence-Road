<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTranslation extends Model
{
    use HasFactory;

    protected $table = 'payment_translations';
    protected $fillable = ['payment_id','locale','status'];
}
