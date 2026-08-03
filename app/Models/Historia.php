<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $table = 'historias';

    protected $fillable = [
        'orden',  // corregido de '
        'ano',
        'titulo',
        'descripcion',
        'imagen',
    ];
}
