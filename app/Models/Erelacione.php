<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Erelacione extends Model
{
    protected $table = 'erelaciones';

    protected $fillable = ['equipo_id', 'equipo_relacionado_id'];
}
