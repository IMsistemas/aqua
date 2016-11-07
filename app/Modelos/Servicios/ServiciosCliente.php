<?php

namespace App\Modelos\Servicios;

use Illuminate\Database\Eloquent\Model;

class ServiciosCliente extends Model
{
    protected $table = "servicioscliente";
    public $incrementing = false;
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigoclientenuevo');
    }

    public function serviciojunta()
    {
        return $this->belongsTo('App\Modelos\Servicios\ServicioJunta','idserviciojunta');
    }
}
