<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_RetencionCompra extends Model
{
    protected $table = 'sri_retencioncompra';
    protected $primaryKey = 'idretencioncompra';
    public $timestamps = false;

    public function cont_documentocompra()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_DocumentoCompra','iddocumentocompra');
    }

    public function sri_retenciondetallecompra()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_RetencionDetalleCompra','idretencioncompra');
    }
}
