<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class productoenbodega extends Model
{
	protected $table = "productoenbodega";

    protected $primaryKey = "idproductobodega";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idproductobodega', 
        'idbodega',
        'codigoproducto',
        'cantidadproductobodega'
    ];

    public function bodega(){
    	return $this->belongsTo('App\Modelos\Bodegas\Bodega','idbodega');
    }

    public function catalogoproducto(){
    	return $this->belongsTo('App\Modelos\CatalogoProductos\CatalogoProducto','codigoproducto');
    }
}
