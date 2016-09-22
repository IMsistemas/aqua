<?php
 
namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CuentasPorCobrarSuministro extends Model
{
    protected $table = "cuentaporcobrarsuministro";
    public $timestamps = false;
    protected $primaryKey = "idcxc";
    
    public function cliente (){
    	return $this->belongsTo('App\Modelos\Clientes\Cliente');
    }
}
   