<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;


class CobroAgua extends Model
{
     protected $table = "cobroagua";
     protected $primaryKey = "idcobroagua";
     public $timestamps = false;

    public function lectura()
    {
        return $this->belongsTo('App\Modelos\Lecturas\Lectura','idlectura');
    }

    public function suministro()
    {
        return $this->belongsTo('App\Modelos\Suministros\Suministro','idsuministro');
    }

    public function factura()
    {
        return $this->belongsTo('App\Modelos\Facturas\Factura','idfactura');
    }

}
