<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageTranslation extends Model
{
    use HasFactory;

    protected $table = 'package_translations';
    protected $fillable = ['package_id','locale','name','description'];
}
