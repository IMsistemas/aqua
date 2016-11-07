<?php

namespace App\Modelos\Servicios;

use Illuminate\Database\Eloquent\Model;

class ServiciosTipoCliente extends Model
{
    protected $table = "serviciostipocliente";
    public $incrementing = false;
    public $timestamps = false;

    public function serviciojunta()
    {
        return $this->belongsTo('App\Modelos\Servicios\ServicioJunta', 'idserviciojunta');
    }

    public function tipocliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\TipoCliente', 'id');
    }
}
