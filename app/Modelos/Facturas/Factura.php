<?php

namespace App\Modelos\Facturas;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{

    protected $table = "factura";
    protected $primaryKey = "numerofactura";
    public $timestamps = false;


    public function cobroagua()
    {
        return $this->belongsTo('App\Modelos\Cuentas\CobroAgua','idcobroagua');
    }


    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente','codigocliente');
    }
}
