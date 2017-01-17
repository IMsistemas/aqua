<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class registrocliente extends Model
{
    protected $table = "cont_registrocliente";

    protected $primaryKey = "idregistrocliente";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idregistrocliente',
        'idcliente',
        'idtransaccion',
        'fecha',
        'debe',
        'haber',
        'numerodocumento'
    ];

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente',"idcliente");
    }
    public function transaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\transaccion',"idtransaccion");
    }
}
