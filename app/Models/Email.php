<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'password', 'phone', 'full_name', 'text_info', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
