<?php
 
namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;


class CobroAgua extends Model
{
     protected $table = "cobroagua";
     protected $primaryKey = "idcuenta";
     public $timestamps = false;

     public function suministro(){
    	return $this->belongsTo('App\Modelos\Suministros\suministro','numerosuministro');
    }

    public function lectura(){
    	return $this->belongsTo('App\Modelos\Lecturas\lectura','idlectura');
    }

    public function rubrosvariables(){
    	return $this->belongsToMany('App\Modelos\Cuentas\rubrovariable','rubrosvariablescuenta','idcuenta','idrubrovariable')->withPivot('costorubro');
    }

    public function rubrosfijos(){
    	return $this->belongsToMany('App\Modelos\Cuentas\rubrofijo','rubrosfijoscuenta','idcuenta','idrubrofijo');
    }

}
