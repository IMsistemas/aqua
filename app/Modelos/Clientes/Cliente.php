<?php 

namespace App\Modelos\Clientes;

use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
    protected $table = "cliente";
    protected $primaryKey = "codigocliente";
    public $timestamps = false;
    public $incrementing = false;

    public function solicitud(){
    	return $this->hasMany('App\Modelos\Suministros\Solicitud','codigocliente');
    }

    public function suministro(){
    	return $this->hasMany('App\Modelos\Suministros\Suministro','codigocliente');
    }

    public function cuentasporpagarclientes(){
    	return $this->hasMany('App\Modelos\Cuentas\Cuentas\Cuentasporpagarclientes','codigocliente');
    }

    public function cuentasporcobrarsuministro(){
    	return $this->hasMany('App\Modelos\Cuentas\Cuentas\Cuentasporcobrarsuministro','codigocliente');
    }
}
 