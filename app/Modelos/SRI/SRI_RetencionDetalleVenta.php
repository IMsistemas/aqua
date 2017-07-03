<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_RetencionDetalleVenta extends Model
{
    protected $table = 'sri_retenciondetalleventa';
    protected $primaryKey = 'idretenciondetalleventa';
    public $timestamps = false;

    public function sri_detalleimpuestoretencion()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_DetalleImpuestoRetencion','iddetalleimpuestoretencion');
    }
}
