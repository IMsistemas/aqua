<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_RegistroCliente extends Model
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
        'numerodocumento',
        'estadoanulado'
    ];

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente',"idcliente");
    }
    public function cont_transaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_Transaccion',"idtransaccion");
    }
}
