<?php

namespace App\Modelos\Clientes;

use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    protected $table = "tipocliente";
    protected $primaryKey = "idtipocliente";
    public $timestamps = false;

    public function cliente()
    {
        return $this->hasMany('App\Modelos\Clientes\Cliente','idtipocliente');
    }

    public function serviciostipocliente()
    {
        return $this->hasMany('App\Modelos\Servicios\ServiciosTipoCliente', 'id');
    }

}
