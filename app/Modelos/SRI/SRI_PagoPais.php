<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_PagoPais extends Model
{

    protected $table = 'sri_pagopais';
    protected $primaryKey = 'idpagopais';
    public $timestamps = false;

    public function sri_comprobanteretencion()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_ComprobanteRetencion','idpagopais');
    }

}
