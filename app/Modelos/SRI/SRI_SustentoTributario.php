<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_SustentoTributario extends Model
{

    protected $table = 'sri_sustentotributario';
    protected $primaryKey = 'idsustentotributario';
    public $timestamps = false;

    public function sri_sustento_comprobante()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_Sustento_Comprobante', 'idsustentotributario');
    }

}
