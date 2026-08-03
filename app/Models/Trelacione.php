<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trelacione extends Model
{
    protected $table = 'trelaciones';

    protected $fillable = ['trabajo_id', 'trabajo_relacionado_id'];
}
