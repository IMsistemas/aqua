<?php
 
namespace App\Modelos\Suministros;

use Illuminate\Database\Eloquent\Model;
 
class Suministro extends Model
{
    protected $table = "suministro";
    protected $primaryKey = "numerosuministro";
    public $timestamps = false; 

    public function cliente(){
    	return $this->belongsTo('App\Modelos\Clientes\Cliente','documentoidentidad');
    }

    public function calle(){
    	return $this->belongsTo('App\Modelos\Sectores\Calle','idcalle');
    }

    public function producto(){
    	return $this->belongsTo('App\Modelos\Suministros\producto','idproducto');
    }

    public function tarifa(){
    	return $this->belongsTo('App\Modelos\Tarifas\tarifa','idtarifa');
    }

    public function lectura(){
    	return $this->hasMany('App\Modelos\Lecturas\lectura','numerosuministro');
    }

    public function cobroagua(){
    	return $this->hasMany('App\Modelos\Cuentas\cobroagua','numerosuministro');
    }

}
