<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "slug",
        "summary",
        "content",
        "stack",
        "github_url",
        "image_path",
        "is_featured",
        "ari_context",
    ];

    protected $casts = [
        "stack" => "array",
        "is_featured" => "boolean",
    ];
}
