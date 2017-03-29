<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoImpuesto extends Model
{

    protected $table = 'sri_tipoimpuesto';
    protected $primaryKey = 'idtipoimpuesto';
    public $timestamps = false;

    public function sri_tipoimpuestoice()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_TipoImpuestoIce','idtipoimpuesto');
    }

    public function sri_tipoimpuestoiva()
    {
        return $this->hasMany('App\Modelos\SRI\SRI_TipoImpuestoIva','idtipoimpuesto');
    }

}
