<?php

namespace App\Modelos\Facturas;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{

    protected $table = "facturacobro";
    protected $primaryKey = "idfactura";
    public $timestamps = false;


    public function cobroagua()
    {
        return $this->belongsTo('App\Modelos\Cuentas\CobroAgua','idcobroagua');
    }


    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente','codigocliente');
    }

    public function serviciosenfactura(){
        return $this->hasMany('App\Modelos\Servicios\ServiciosEnFactura','idfactura');
    }

    public function otrosvaloresfactura(){
        return $this->hasMany('App\Modelos\Facturas\OtrosValoresFactura','idfactura');
    }




}
