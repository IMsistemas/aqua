<?php
 
namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CuentasPorPagarClientes extends Model
{
    protected $table = "cuentasporpagarclientes";
    public $timestamps = false;

    public function cliente (){
    	return $this->belongsTo('App\Modelos\Clientes\cliente');
    }
}
 