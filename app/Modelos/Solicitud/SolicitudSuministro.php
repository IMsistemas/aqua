<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolicitudSuministro extends Model
{
    protected $table = 'solicitudsuministro';
    protected $primaryKey = 'idsolicitudsuministro';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'idcliente');
    }

    public function suministro()
    {
        return $this->belongsTo('App\Modelos\Suministros\Suministro', 'idsuministro');
    }

}
