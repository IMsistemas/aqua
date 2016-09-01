<?php
 
namespace App\Modelos\Lecturas;

use Illuminate\Database\Eloquent\Model;

class Lectura extends Model
{
    protected $table = "lectura";
    protected $primaryKey = "idlectura";
    public $timestamps = false;

    public function suministro(){
    	return $this->belongsTo('App\Modelos\Suministros\suministro');
    }

    public function cobroagua(){
    	return $this->hasMany('App\Modelos\Cuentas\cobroagua','idlectura');
    }
}
