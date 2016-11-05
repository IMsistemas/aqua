<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolicitudServicio extends Model
{
    protected $table = 'solicitudservicio';
    protected $primaryKey = 'idsolicitudservicio';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigocliente');
    }

}
