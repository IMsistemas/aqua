<?php

namespace App\Modelos\Servicios;

use Illuminate\Database\Eloquent\Model;

class ServiciosEnFactura extends Model
{
    protected $table = "serviciosenfactura";
    public $timestamps = false;


    public function factura()
    {
        return $this->belongsTo('App\Modelos\Facturas\Factura','numerofactura');
    }


    public function serviciojunta()
    {
        return $this->belongsTo('App\Modelos\Servicios\ServicioJunta','idserviciojunta');
    }


}
