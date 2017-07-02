<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_RetencionVenta extends Model
{
    protected $table = 'sri_retencionventa';
    protected $primaryKey = 'idretencionventa';
    public $timestamps = false;

    public function cont_documentoventa()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_DocumentoVenta','iddocumentoventa');
    }


    public function sri_retenciondetalleventa()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_RetencionDetalleVenta','idretencionventa');
    }
}
