<?php
 
namespace App\Modelos\Suministros;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = "solicitud";
    protected $primaryKey = "idsolicitud";
    public $timestamps = false; 

    public function cliente(){
    	return $this->belongsTo('App\Modelos\Clientes\Cliente','documentoidentidad');
    }
}
  