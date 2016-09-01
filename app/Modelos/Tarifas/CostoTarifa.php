<?php
 
namespace App\Modelos\Tarifas;

use Illuminate\Database\Eloquent\Model;

class CostoTarifa extends Model
{
    protected $table = "costotarifa";
    public $timestamps = false;

    public function tarifa (){
    	return $this->belongsTo('App\Modelos\Tarifas\tarifa');
    }
}
