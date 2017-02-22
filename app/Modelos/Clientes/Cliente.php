<?php 

namespace App\Modelos\Clientes;

use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
    protected $table = "cliente";
    protected $primaryKey = "idcliente";
    public $timestamps = false;


    public function tipocliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\TipoCliente','id');
    }

    public function servicioscliente()
    {
        return $this->hasMany('App\Modelos\Servicios\ServiciosCliente', 'idcliente');
    }

    public function cuentasporpagarclientes()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasPorPagarClientes', 'idcliente');
    }


    public function cuentaporcobrarsuministro()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasPorCobrarSuministro','idcliente');
    }

}
