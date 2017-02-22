<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_Sustento_Comprobante extends Model
{

    protected $table = 'sri_sustento_comprobante';
    protected $primaryKey = false;
    public $timestamps = false;

    public function sri_sustentotributario()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_SustentoTributario','idsustentotributario');
    }

    public function sri_tipocomprobante()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoComprobante','idtipocomprobante');
    }

}
