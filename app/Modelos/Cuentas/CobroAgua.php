<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;


class CobroAgua extends Model
{
     protected $table = "cobroagua";
     protected $primaryKey = "idcuenta";
     public $timestamps = false;

     public function suministro(){
    	return $this->belongsTo('App\Modelos\Suministros\Suministro','numerosuministro');
    }

    public function lectura(){
    	return $this->belongsTo('App\Modelos\Lecturas\Lectura','idlectura');
    }

    public function rubrosvariables(){
    	return $this->belongsToMany('App\Modelos\Cuentas\RubroVariable','rubrosvariablescuenta','idcuenta','idrubrovariable')->withPivot('costorubro');
    }

    public function rubrosfijos(){
    	return $this->belongsToMany('App\Modelos\Cuentas\RubroFijo','rubrosfijoscuenta','idcuenta','idrubrofijo')->withPivot('costorubro');
    }

}
