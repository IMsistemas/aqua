<?php 

namespace App\Modelos\Clientes;

use Illuminate\Database\Eloquent\Model;


class Cliente extends Model
{
    protected $table = "cliente";
    protected $primaryKey = "documentoidentidad";
    public $timestamps = false;
    public $incrementing = false;

    public function solicitud(){
    	return $this->hasMany('App\Modelos\Suministros\Solicitud','documentoidentidad');
    }

    public function suministro(){
    	return $this->hasMany('App\Modelos\Suministros\Suministro','documentoidentidad');
    }

    public function cuentasporpagarclientes(){
    	return $this->hasMany('App\Modelos\Cuentas\Cuentas\Cuentasporpagarclientes','documentoidentidad');
    }

    public function cuentasporcobrarsuministro(){
    	return $this->hasMany('App\Modelos\Cuentas\Cuentas\Cuentasporcobrarsuministro','documentoidentidad');
    }
}
 