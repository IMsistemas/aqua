<?php

namespace App\Modelos\Servicios;

use Illuminate\Database\Eloquent\Model;

class ServiciosEnFactura extends Model
{
    protected $table = "serviciosenfactura";
    protected $primaryKey = null;
    public $incrementing = false;
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
