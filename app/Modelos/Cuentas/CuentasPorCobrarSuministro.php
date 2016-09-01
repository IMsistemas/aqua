<?php
 
namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CuentasPorCobrarSuministro extends Model
{
    protected $table = "cuentasporcobrarsuministro";
    public $timestamps = false;

    public function cliente (){
    	return $this->belongsTo('App\Modelos\Clientes\cliente');
    }
}
 