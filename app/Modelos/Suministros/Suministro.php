<?php
 
namespace App\Modelos\Suministros;

use Illuminate\Database\Eloquent\Model;
 
class Suministro extends Model
{
    protected $table = "suministro";
    protected $primaryKey = "idsuministro";
    public $timestamps = false; 

    public function cliente()
    {
    	return $this->belongsTo('App\Modelos\Clientes\Cliente','idcliente');
    }

    public function calle()
    {
    	return $this->belongsTo('App\Modelos\Sectores\Calle','idcalle');
    }

    public function producto()
    {
    	return $this->belongsTo('App\Modelos\Suministros\Producto','idproducto');
    }

    public function tarifaaguapotable()
    {
    	return $this->belongsTo('App\Modelos\Servicios\AguaPotable','idtarifaaguapotable');
    }

    public function cuentaporcobrarsuministro()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasPorCobrarSuministro','idsuministro');
    }

}
