<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prelacione extends Model
{
    protected $table = 'prelaciones';

    protected $fillable = ['producto_id', 'producto_relacionado_id'];
}
