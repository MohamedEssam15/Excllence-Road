<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitTranslation extends Model
{
    use HasFactory;
    protected $table = 'units_translations';
    protected $fillable = ['unit_id','locale','name'];

    
}
