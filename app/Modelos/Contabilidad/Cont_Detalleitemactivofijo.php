<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_detalleitemactivofijo extends Model
{
    protected $table = 'cont_detalleitemactivofijo';
    protected $primaryKey = 'iddetalleitemactivofijo';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iditemactivofijo', 'iditemcompra', 'idempleado','idplandecuentadepreciacion','idplancuentagasto','numactivo','vidautil','fechaalta','valorsalvamento','precioventa','estado','ubicacion','observacion'       
    ];

  
}
