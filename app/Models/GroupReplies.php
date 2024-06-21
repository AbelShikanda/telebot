<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupReplies extends Model
{
    use HasFactory;
    protected $fillable = ['keyword', 'response', 'default_response'];
}
