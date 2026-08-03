<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rede extends Model
{
    protected $fillable = [
        'instagram',
        'facebook',
        'youtube',
        'linkedin',
       // 'tiktok',
    ];
}
