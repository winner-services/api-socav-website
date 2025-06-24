<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temoignage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'fonction_en',
        'fonction_fr',
        'description_en',
        'description_fr',
        'image'
    ];
}
