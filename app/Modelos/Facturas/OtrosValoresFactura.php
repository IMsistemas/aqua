<?php

namespace App\Modelos\Facturas;

use Illuminate\Database\Eloquent\Model;

class OtrosValoresFactura extends Model
{

    protected $table = "otrosvaloresfactura";
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;


    public function otrovalor()
    {
        return $this->belongsTo('App\Modelos\Facturas\OtrosValores','idotrovalor');
    }


    public function facturacobro()
    {
        return $this->belongsTo('App\Modelos\Facturas\Factura','idfactura');
    }




}
