<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramChats extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id', 
        'type', 
        'title', 
    ];

    public function messages()
    {
        return $this->hasMany(TelegramMessages::class, 'chat_id', 'chat_id');
    }
}
