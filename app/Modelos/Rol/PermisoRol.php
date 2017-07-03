<?php

namespace App\Modelos\Rol;

use Illuminate\Database\Eloquent\Model;

class PermisoRol extends Model
{
    protected $table = 'permiso_rol';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function rol()
    {
        return $this->belongsTo('App\Modelos\Rol\Rol', 'idrol');
    }

    public function permiso()
    {
        return $this->belongsTo('App\Modelos\Rol\Permiso', 'idpermiso');
    }

}
