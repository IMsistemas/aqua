<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolicitudMantenimiento extends Model
{
    protected $table = 'solicitudmantenimiento';
    protected $primaryKey = 'idsolicitudmantenimiento';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigocliente');
    }

    public function suministro()
    {
        return $this->belongsTo('App\Modelos\Suministros\Suministro', 'numerosuministro');
    }
}
