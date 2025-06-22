<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_en',
        'question_fr',
        'response_fr',
        'response_en',
    ];
}
