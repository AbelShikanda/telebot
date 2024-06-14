<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramMessages extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id', 'chat_id', 'user_id', 'text', 'caption', 'media_type',
        'file_id', 'is_forwarded', 'is_reply', 'reply_to_message_id', 
        'has_media', 'has_document', 'has_location', 
    ];

    public function chats()
    {
        return $this->belongsToMany(TelegramChats::class, 'telegram_chat_user', 'user_id', 'chat_id')
                    ->withPivot('is_admin')
                    ->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(TelegramMessages::class, 'user_id', 'user_id');
    }
}
