<?php

namespace App\Modelos\Rol;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'idrol';
    public $timestamps = false;

    public function permiso_rol()
    {
        return $this->hasMany('App\Modelos\Rol\PermisoRol', 'idrol');
    }
}
