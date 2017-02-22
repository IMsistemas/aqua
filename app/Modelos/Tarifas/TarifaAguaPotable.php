<?php

namespace App\Modelos\Tarifas;

use Illuminate\Database\Eloquent\Model;

class TarifaAguaPotable extends Model
{
    protected $table = 'tarifaaguapotable';
    protected $primaryKey = 'idtarifaaguapotable';
    public $timestamps = false;

    public function suministro(){
        return $this->hasMany('App\Modelos\Suministros\Suministro','idtarifaaguapotable');
    }

    public function costotarifa(){
        return $this->hasMany('App\Modelos\Tarifas\CostoTarifa','idtarifaaguapotable');
    }

    public function excedentetarifa(){
        return $this->hasMany('App\Modelos\Tarifas\ExcedenteTarifa','idtarifaaguapotable');
    }
}
