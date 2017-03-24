<?php
 
namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_ProductoBodega extends Model
{
    protected $table = "cont_producto_bodega";

    protected $primaryKey = "idproducto_bodega";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idproducto_bodega',
        'idcatalogitem',
        'idbodega'
    ];
    public function cont_catalogoitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem');
    }
    public function cont_bodega(){
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_Bodega',"idbodega");
    }

   
}
