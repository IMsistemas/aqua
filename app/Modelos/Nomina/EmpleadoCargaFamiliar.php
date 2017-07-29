<?php

namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class EmpleadoCargaFamiliar extends Model
{
    protected $table = 'empleado_cargafamiliar';
    protected $primaryKey = 'idempleado_cargafamiliar';
    public $timestamps = false;

    public function empleado()
    {
        return $this->belongsTo('App\Modelos\Nomina\Empleado', 'idempleado');
    }
}
