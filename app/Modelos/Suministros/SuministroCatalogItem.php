<?php

namespace App\Modelos\Suministros;

use Illuminate\Database\Eloquent\Model;

class SuministroCatalogItem extends Model
{

    protected $table = "suministro_catalogitem";
    protected $primaryKey = "idsuministro_catalogitem";
    public $timestamps = false;

    public function cont_catalogitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem', 'idcatalogitem');
    }

    public function cobroagua()
    {
        return $this->belongsTo('App\Modelos\Suministros\Suministro', 'idsuministro');
    }

}
