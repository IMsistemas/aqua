<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolicitudCambioNombre extends Model
{
    protected $table = 'solicitudcambionombre';
    protected $primaryKey = 'idsolicitudcambionombre';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigoclientenuevo');
    }

}
