<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoImpuestoIce extends Model
{

    protected $table = 'sri_tipoimpuestoice';
    protected $primaryKey = 'idtipoimpuestoice';
    public $timestamps = false;

    public function sri_tipoimpuesto()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoImpuesto','idtipoimpuesto');
    }

}
