<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;
    protected $fillable = [
        'title_en',
        'title_fr',
        'date',
        'description_en',
        'description_fr',
        'image'
    ];
}
