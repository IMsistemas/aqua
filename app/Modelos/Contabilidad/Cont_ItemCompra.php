<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_ItemCompra extends Model
{

    protected $table = 'cont_itemcompra';
    protected $primaryKey = 'iditemcompra';
    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'iditemcompra',
        'idcatalogitem',
        'iddocumentocompra',
        'idbodega',
        'idtipoimpuestoiva',
        'idtipoimpuestoice',
        'cantidad',
        'preciounitario',
        'descuento',
        'preciototal',
        'iddepartamento'
    ];

    public function cont_documentocompra()
    {
    	return $this->belongsTo('App\Modelos\Contabilidad');
    }

    public function cont_catalogitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem',"idcatalogitem");
    }

}
