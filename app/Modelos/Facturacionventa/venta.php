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
}
