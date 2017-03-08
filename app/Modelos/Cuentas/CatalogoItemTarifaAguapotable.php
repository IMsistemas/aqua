<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CatalogoItemTarifaAguapotable extends Model
{
    protected $table = 'catalogoitem_tarifaaguapotable';
    protected $primaryKey = null;
    public $timestamps = false;

    public function cont_catalogoitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogoItem', 'idcatalogitem');
    }

    public function tarifaaguapotable()
    {
        return $this->belongsTo('App\Modelos\Tarifas\TarifaAguaPotable', 'idtarifaaguapotable');
    }
}
