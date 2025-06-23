<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    protected $fillable = [
        'objective_en',
        'objective_fr',
        'title_en',
        'title_fr',
        'short_description_en',
        'short_description_fr',
        'address',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
        'email',
        'phone',
        'image1',
        'image2'
    ];
}
