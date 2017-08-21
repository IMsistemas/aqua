<?php 

namespace App\Modelos\Clientes;

use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
    protected $table = "cliente";
    protected $primaryKey = "idcliente";
    public $timestamps = false;

    public function persona()
    {
        return $this->belongsTo('App\Modelos\Persona','idpersona');
    }

    public function tipocliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\TipoCliente','id');
    }

    public function sri_tipoimpuestoiva()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoImpuestoIva','idtipoimpuestoiva');
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

    public function cont_plancuenta()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_PlanCuenta','idplancuenta');
    }

    public function sri_tipoempresa()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoEmpresa','idtipoempresa');
    }

    public function sri_parte()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_Parte','idparte');
    }

}
