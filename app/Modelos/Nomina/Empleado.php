<?php
 
namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = "empleado";
    protected $primaryKey = "documentoidentidadempleado";
    public $timestamps = false;

    public function cargo(){
    	return $this->belongsTo('App\Modelos\Nomina\cargo');
    }
}
