<?php

namespace App\Modelos\Rol;

use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    protected $table = 'permiso';
    protected $primaryKey = 'idpermiso';
    public $timestamps = false;
}
