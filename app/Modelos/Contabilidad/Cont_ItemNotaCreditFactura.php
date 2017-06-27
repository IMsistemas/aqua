<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_ItemNotaCreditFactura extends Model
{
    protected $table = 'cont_itemnotacreditfactura';
    protected $primaryKey = 'iditemnotacreditfactura';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'iditemnotacreditfactura',
        'idcatalogitem',
        'iddocumentonotacreditfactura',
        'idbodega',
        'idtipoimpuestoiva',
        'idtipoimpuestoice',
        'cantidad',
        'preciounitario',
        'descuento',
        'preciototal'
    ];

    public function cont_documentonotacreditfactura()
    {
        return $this->belongsTo('App\Modelos\Contabilidad');
    }

    public function cont_catalogoitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem',"idcatalogitem");
    }
}
