<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'title_en',
        'title_fr',
        'facebook',
        'twitter',
        'email',
        'linkedin',
        'image'
    ];
}
