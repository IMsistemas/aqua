<?php 

namespace App\Modelos\Tarifas;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = "tarifa";
    protected $primaryKey = "idtarifa";
    public $timestamps = false;

    public function suministro(){
    	return $this->hasMany('App\Modelos\Suministros\Suministro','idtarifa');
    }

    public function costotarifa(){
    	return $this->hasMany('App\Modelos\Tarifas\CostoTarifa','idtarifa');
    }

    public function excedentetarifa(){
    	return $this->hasMany('App\Modelos\Tarifas\ExcedenteTarifa','idtarifa');
    }
}
