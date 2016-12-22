<?php

namespace App\Modelos\Facturas;

use Illuminate\Database\Eloquent\Model;

class OtrosValores extends Model
{
    protected $table = "otrovalor";
    protected $primaryKey = "idotrovalor";
    public $timestamps = false;

    public function otrosvaloresfactura()
    {
        return $this->hasMany('App\Modelos\Facturas\OtrosValoresFactura', 'idotrovalor');
    }
}
