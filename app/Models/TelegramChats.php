<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramChats extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id', 'type', 'last_update'
    ];

    public function messages()
    {
        return $this->hasMany(TelegramMessages::class, 'chat_id', 'chat_id');
    }

    public function users()
    {
        return $this->belongsToMany(TelegramUsers::class, 'telegram_chat_user', 'chat_id', 'user_id')
                    ->withPivot('is_admin')
                    ->withTimestamps();
    }
}
