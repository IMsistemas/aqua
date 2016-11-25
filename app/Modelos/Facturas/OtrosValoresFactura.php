<?php

namespace App\Modelos\Facturas;

use Illuminate\Database\Eloquent\Model;

class OtrosValoresFactura extends Model
{

    protected $table = "otrosvaloresfactura";
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;


    public function otrosvalores()
    {
        return $this->belongsTo('App\Modelos\Facturas\OtrosValores','idotrosvalores');
    }


    public function factura()
    {
        return $this->belongsTo('App\Modelos\Facturas\Factura','idfactura');
    }




}
