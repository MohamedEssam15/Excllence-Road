<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAttchment extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'user_id'];
    protected $table = 'users_attchments';
    // protected $with=['attachPath'];

    public function attachPath()
    {
        return asset('users_attachments/' . $this->user_id . '/attachments/' . $this->name);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
