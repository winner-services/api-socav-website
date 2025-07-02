<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'title_en',
        'title_fr',
        'description_fr',
        'description_en',
        'image',
        'date'
    ];
}
