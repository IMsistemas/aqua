<?php

namespace App\Modelos\Facturas;

use Illuminate\Database\Eloquent\Model;

class OtrosValores extends Model
{
    protected $table = "otrosvalores";
    protected $primaryKey = "idotrosvalores";
    public $timestamps = false;

    public function otrosvaloresfactura()
    {
        return $this->hasMany('App\Modelos\Facturas\OtrosValoresFactura', 'idotrosvalores');
    }
}
