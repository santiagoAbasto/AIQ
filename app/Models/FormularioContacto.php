<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormularioContacto extends Model
{
    use HasFactory;

    protected $table = 'formularios_contacto';
    protected $fillable = ['name', 'surname', 'email', 'phone', 'message'];
}
