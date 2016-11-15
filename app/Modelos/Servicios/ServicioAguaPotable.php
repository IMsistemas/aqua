<?php

namespace App\Modelos\Servicios;

use Illuminate\Database\Eloquent\Model;

class ServicioAguaPotable extends Model
{

    protected $table = 'serviciosaguapotable';
    protected $primaryKey = null;
    public $timestamps = false;

    public function serviciojunta()
    {
        return $this->belongsTo('App\Modelos\Servicios\ServicioJunta', 'idserviciojunta');
    }

    public function aguapotable()
    {
        return $this->belongsTo('App\Modelos\Servicios\AguaPotable', 'idtarifaaguapotable');
    }

}
