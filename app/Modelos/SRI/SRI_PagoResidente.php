<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_PagoResidente extends Model
{

    protected $table = 'sri_pagoresidente';
    protected $primaryKey = 'idpagoresidente';
    public $timestamps = false;

    public function sri_comprobanteretencion()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_ComprobanteRetencion','idpagoresidente');
    }

}
