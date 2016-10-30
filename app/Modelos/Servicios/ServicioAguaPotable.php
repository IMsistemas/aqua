<?php

namespace App\Modelos\Servicios;

use Illuminate\Database\Eloquent\Model;

class ServicioAguaPotable extends Model
{

    protected $table = "servicioaguapotable";
    protected $primaryKey = "idaguapotable";
    public $timestamps = false;

    public function suministros(){
        return $this->hasMany('App\Modelos\Suministros\Sumministro','idaguapotable');
    }
}
