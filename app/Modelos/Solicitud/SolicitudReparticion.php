<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolicitudReparticion extends Model
{
    protected $table = 'solicitudreparticion';
    protected $primaryKey = 'idsolicitudreparticion';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigocliente');
    }

    public function terreno()
    {
        return $this->belongsTo('App\Modelos\Terreno\Terreno', 'idterreno');
    }
}
