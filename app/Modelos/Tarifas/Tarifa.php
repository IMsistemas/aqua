<?php 

namespace App\Modelos\Tarifas;

use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    protected $table = "tarifa";
    protected $primaryKey = "idtarifa";
    public $timestamps = false;

    public function suministro(){
    	return $this->hasMany('App\Modelos\Suministros\suministro','idtarifa');
    }

    public function costotarifa(){
    	return $this->hasMany('App\Modelos\Tarifas\costotarifa','idtarifa');
    }

    public function excedentetarifa(){
    	return $this->hasMany('App\Modelos\Tarifas\excedentetarifa','idtarifa');
    }
}
