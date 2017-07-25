<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_DetalleImpuestoRetencion extends Model
{

    protected $table = 'sri_detalleimpuestoretencion';
    protected $primaryKey = 'iddetalleimpuestoretencion';
    public $timestamps = false;

    public function sri_tipoimpuestoretencion()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoImpuestoRetencion','idtipoimpuestoretencion');
    }

    public function cont_plancuenta()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_PlanCuenta','idplancuenta');
    }

}
