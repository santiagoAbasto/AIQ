<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContenidoBobina extends Model
{
    protected $table = 'contenido_bobinas';

    protected $fillable = [
        'descripcion',
    ];
}
