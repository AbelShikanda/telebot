<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUsers extends Model
{
    use HasFactory;

    protected $table = 'telegram_users';

    protected $fillable = [
        'user_id', 
        'username', 
        'first_name', 
        'last_name', 
        'warning_count',
        'last_warning_at', 
        'joined_at', 
        'message_count', 
    ];

    public function messages()
    {
        return $this->hasMany(TelegramMessages::class, 'user_id', 'id');
    }
}
