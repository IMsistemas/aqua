<?php

namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class EmpleadoRegistroSalarial extends Model
{
    protected $table = 'empleado_registrosalarial';
    protected $primaryKey = 'idempleado_registrosalarial';
    public $timestamps = false;

    public function empleado()
    {
        return $this->belongsTo('App\Modelos\Nomina\Empleado', 'idempleado');
    }
}
