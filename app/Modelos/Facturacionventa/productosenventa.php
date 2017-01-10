<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class productosenventa extends Model
{
	protected $table = "productosenventa";

    protected $primaryKey = "idproductoenventa";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idproductoenventa',
        'codigoventa',
        'codigoproducto',
        'idbodega',
        'cantidad',
		'precio',
		'preciototal',
        'porcentajeiva'
    ];

    public function producto()
    {
        return $this->belongsTo('App\Modelos\Facturacionventa\catalogoproducto',"codigoproducto");
    }
}
