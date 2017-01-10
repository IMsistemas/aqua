<?php

namespace App\Modelos\Retencion;

use Illuminate\Database\Eloquent\Model;

class RetencionVenta extends Model
{
    protected $table = "retencionventa";
    protected $primaryKey = "idretencionventa";
    public $timestamps = false;

    public function venta()
    {
        return $this->belongsTo('App\Modelos\Facturacionventa\venta','codigoventa');
    }
}
