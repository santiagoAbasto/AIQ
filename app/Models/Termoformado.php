<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Termoformado extends Model
{
    protected $table = 'termoformados';

    protected $fillable = [
        'descripcion',
    
    ];

    // galeria json
    protected $casts = [
        'galeria' => 'array',
    ];

}
