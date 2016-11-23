<?php
 
namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;


class CuentasPorPagarClientes extends Model
{
    protected $table = "cuentasporpagarclientes";
    public $timestamps = false;
    protected $primaryKey = "idcxp";

    public function cliente (){
    	return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigocliente');
    }
}
 