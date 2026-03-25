<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        "company",
        "position",
        "start_date",
        "end_date",
        "description",
        "ari_context",
    ];

    protected $casts = [
        "start_date" => "date",
        "end_date" => "date",
    ];
}
