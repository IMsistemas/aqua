<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoComprobante extends Model
{

    protected $table = 'sri_tipocomprobante';
    protected $primaryKey = 'idtipocomprobante';
    public $timestamps = false;

    public function sri_sustento_comprobante()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_Sustento_Comprobante', 'idtipocomprobante');
    }

}
