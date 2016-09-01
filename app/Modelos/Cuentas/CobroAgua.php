<?php
 
namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;


class CobroAgua extends Model
{
     protected $table = "cobroagua";
     protected $primaryKey = "idcuenta";
     public $timestamps = false;

     public function suministro(){
    	return $this->belongsTo('App\Modelos\Suministros\suministro');
    }

    public function lectura(){
    	return $this->belongsTo('App\Modelos\Lecturas\lectura');
    }

    public function rubrosvariables(){
    	return $this->belongsToMany('App\Modelos\Cuentas\rubrovariable','rubrosvariablescuentas','idcuenta','idrubrovariable');
    }

    public function rubrosfijos(){
    	return $this->belongsToMany('App\Modelos\Cuentas\rubrofijo','rubrosfijoscuentas','idcuenta','idrubrofijo');
    }

}
