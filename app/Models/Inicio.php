<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inicio extends Model
{
    use HasFactory;
    protected $table = 'inicios';
    protected $fillable = [
        'id',
        'titulo',
        'descripcion',
        'imagen',
        'titulo_banner',
        'descripcion_banner',
        'banner',
        'banner_dos',
        'titulouno',
        'titulodos',
        'titulotres',
        'imagenuna',
        'imagendos',
        'imagentres',
        'created_at',
        'updated_at'
    ];
}
