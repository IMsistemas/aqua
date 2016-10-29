<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolicitudOtro extends Model
{
    protected $table = 'solicitudotro';
    protected $primaryKey = 'idsolicitudotro';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigocliente');
    }

}
