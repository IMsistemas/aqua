<?php

namespace App\Modelos\Facturas;

use Illuminate\Database\Eloquent\Model;

class OtrosValoresCobroAgua extends Model
{

    protected $table = 'otrosvalores_cobroagua';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;


    public function otrosvalores()
    {
        return $this->belongsTo('App\Modelos\Facturas\OtrosValores','idotrosvalores');
    }


    public function cobroagua()
    {
        return $this->belongsTo('App\Modelos\Facturas\Factura','idcobroagua');
    }
}
