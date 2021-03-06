<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CatalogoItemCobroAgua extends Model
{
    protected $table = 'catalogoitem_cobroagua';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function cont_catalogitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem', 'idcatalogitem');
    }

    public function cobroagua()
    {
        return $this->belongsTo('App\Modelos\Cuentas\CobroAgua', 'idcobroagua');
    }
}
