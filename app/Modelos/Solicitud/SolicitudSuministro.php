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
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigocliente');
    }

}
