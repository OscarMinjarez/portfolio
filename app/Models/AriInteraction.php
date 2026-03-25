<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AriInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        "prompt",
        "response",
        "tokens_used",
        "session_id",
        "is_helpful",
    ];
}
