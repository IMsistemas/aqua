<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoImpuestoIva extends Model
{

    protected $table = 'sri_tipoimpuestoiva';
    protected $primaryKey = 'idtipoimpuestoiva';
    public $timestamps = false;

    public function sri_tipoimpuesto()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoImpuesto','idtipoimpuesto');
    }

}
