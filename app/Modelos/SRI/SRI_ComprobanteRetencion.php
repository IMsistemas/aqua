<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_ComprobanteRetencion extends Model
{

    protected $table = 'sri_comprobanteretencion';
    protected $primaryKey = 'idcomprobanteretencion';
    public $timestamps = false;


    public function sri_pagoresidente()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_PagoResidente','idpagoresidente');
    }

    public function sri_pagopais()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_PagoPais','idpagopais');
    }

    public function cont_documentocompra()
    {
        return $this->hasMany('App\Modelos\Contabilidad\Cont_DocumentoCompra',"idcomprobanteretencion");
    }

}
