<?php

namespace App\Modelos;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{

    protected $table = 'persona';
    protected $primaryKey = 'idpersona';
    public $timestamps = false;

    public function transportista()
    {
    	return $this->hasMany('App\Modelos\Transportista\Transportista', 'idpersona');
    }

    public function cliente()
    {
    	return $this->hasMany('App\Modelos\Clientes\Cliente', 'idpersona');
    }

    public function empleado()
    {
        return $this->hasMany('App\Modelos\Nomina\Empleado', 'idpersona');
    }

    public function proveedor()
    {
        return $this->hasMany('App\Modelos\Proveedores\Proveedor', 'idpersona');
    }
}
