<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CobroHistorial extends Model
{
    protected $table = 'cobrohistorial';
    protected $primaryKey = 'idcobrohistorial';
    public $timestamps = false;


    public function cont_catalogitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem','idcatalogitem');
    }

}
