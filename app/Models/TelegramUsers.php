<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'chat_id', 
        'username', 
        'first_name', 
        'last_name', 
        'warning_count',
        'last_warning_at', 
        'joined_at', 
        'message_count', 
        'is_admin',
    ];

    public function chat()
    {
        return $this->belongsTo(TelegramChats::class, 'chat_id', 'chat_id');
    }

    public function user()
    {
        return $this->belongsTo(TelegramUsers::class, 'user_id', 'user_id');
    }
}
