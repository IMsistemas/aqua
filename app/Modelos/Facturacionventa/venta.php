<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class venta extends Model
{
    protected $table = "documentoventa";

    protected $primaryKey = "codigoventa";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'codigoventa',
        'codigoformapago',
        'codigocliente',
        'idpuntoventa',
        'idempleado',
        'idfactura',
        'numerodocumento',
        'fecharegistrocompra',
        'autorizacionfacturar',
        'subtotalivaventa',
        'descuentoventa',
        'ivaventa',
        'totalventa',
        'otrosvalores',
        'procentajedescuentocompra',
        'estapagada',
        'estaanulada',
        'fechapago',
        'comentario',
        'impreso' 
    ];

    public function puntoventa()
    {
        return $this->belongsTo('App\Modelos\Facturacionventa\puntoventa',"idpuntoventa");
    }
    public function productosenventa()
    {
        return $this->hasMany('App\Modelos\Facturacionventa\productosenventa',"codigoventa");
    }
    public function serviciosenventa()
    {
        return $this->hasMany('App\Modelos\Facturacionventa\serviciosenventa',"codigoventa");
    }
    public function cliente()
    {
        return $this->hasOne('App\Modelos\Clientes\Cliente',"codigocliente");
    }
    public function pago()
    {
        return $this->belongsTo('App\Modelos\Facturacionventa\formapagoventa',"codigoformapago");
    }
}
