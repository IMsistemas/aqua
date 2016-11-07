<?php

namespace App\Modelos\Servicios;

use Illuminate\Database\Eloquent\Model;

class AguaPotable extends Model
{
    protected $table = "aguapotable";
    protected $primaryKey = "idtarifaaguapotable";
    public $timestamps = false;

    public function suministros(){
        return $this->hasMany('App\Modelos\Suministros\Sumministro','idtarifaaguapotable');
    }

    public function serviciosaguapotable()
    {
        return $this->hasMany('App\Modelos\Servicios\ServicioAguaPotable', 'idtarifaaguapotable');
    }
}
