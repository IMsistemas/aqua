<?php
 
namespace App\Modelos\Tarifas;

use Illuminate\Database\Eloquent\Model;

class ExcedenteTarifa extends Model
{
    protected $table = "excedentetarifa";
    public $timestamps = false;

    public function tarifa (){
    	return $this->belongsTo('App\Modelos\Tarifas\tarifa');
    }
}
