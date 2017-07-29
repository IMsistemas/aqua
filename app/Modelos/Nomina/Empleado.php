<?php

namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleado';
    protected $primaryKey = 'idempleado';
    public $timestamps = false;

    public function cargo()
    {
        return $this->belongsTo('App\Modelos\Nomina\Cargo', 'idcargo');
    }

    public function departamento()
    {
        return $this->belongsTo('App\Modelos\Nomina\Departamento', 'iddepartamento');
    }

    public function cont_plancuenta()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_PlanCuenta', 'idplancuenta');
    }

    public function cont_bodega()
    {
        return $this->hasMany('App\Modelos\Contabilidad\Cont_Bodega', 'idempleado');
    }

    public function empleado_cargafamiliar()
    {
        return $this->hasMany('App\Modelos\Nomina\EmpleadoCargaFamiliar', 'idempleado');
    }

    public function empleado_registrosalarial()
    {
        return $this->hasMany('App\Modelos\Nomina\EmpleadoRegistroSalarial', 'idempleado');
    }

    public function persona()
    {
        return $this->belongsTo('App\Modelos\Persona', 'idpersona');
    }

}