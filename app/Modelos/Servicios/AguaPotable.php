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
}
