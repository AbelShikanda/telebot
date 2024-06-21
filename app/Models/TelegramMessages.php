<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramMessages extends Model
{
    use HasFactory;

    protected $table = 'telegram_messages';

    protected $fillable = [
        'message_id', 
        'chat_id', 
        'user_id', 
        'text', 
        'caption',
        'is_reply', 
        'reply_to_message_id', 
    ];

    public function chat()
    {
        return $this->belongsTo(TelegramChats::class, 'chat_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(TelegramUsers::class, 'user_id', 'id');
    }
}
