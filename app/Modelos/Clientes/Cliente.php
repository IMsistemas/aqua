<?php 

namespace App\Modelos\Clientes;

use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
    protected $table = "cliente";
    protected $primaryKey = "codigocliente";
    public $timestamps = false;


    public function tipocliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\TipoCliente','id');
    }

    public function servicioscliente()
    {
        return $this->hasMany('App\Modelos\Servicios\ServiciosCliente', 'codigocliente');
    }

    public function cuentasporpagarclientes()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasPorPagarClientes', 'codigocliente');
    }


    public function cuentaporcobrarsuministro()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasPorCobrarSuministro','codigocliente');
    }

}
