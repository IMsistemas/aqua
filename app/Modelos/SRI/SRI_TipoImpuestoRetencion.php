<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoImpuestoRetencion extends Model
{

    protected $table = 'sri_tipoimpuestoretencion';
    protected $primaryKey = 'idtipoimpuestoretencion';
    public $timestamps = false;

    public function sri_detalleimpuestoretencion()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_DetalleImpuestoRetencion','idtipoimpuestoretencion');
    }

}
