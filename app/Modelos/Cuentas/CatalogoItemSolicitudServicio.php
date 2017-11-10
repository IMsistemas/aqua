<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CatalogoItemSolicitudServicio extends Model
{
    protected $table = 'catalogoitem_solicitudservicio';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    public function cont_catalogitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem', 'idcatalogitem');
    }

    public function solicitudservicio()
    {
        return $this->belongsTo('App\Modelos\Solicitud\SolicitudServicio', 'idsolicitudservicio');
    }
}
