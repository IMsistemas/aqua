<?php

namespace App\Modelos\Servicios;

use Illuminate\Database\Eloquent\Model;

class ServicioJunta extends Model
{
    protected $table = "serviciojunta";
    protected $primaryKey = "idserviciojunta";
    public $timestamps = false;


    public function serviciosenfactura(){
        return $this->hasMany('App\Modelos\Servicios\ServiciosEnFactura','idserviciojunta');
    }


}
