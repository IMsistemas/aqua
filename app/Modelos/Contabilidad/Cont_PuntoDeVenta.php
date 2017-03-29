<?php
 
namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_PuntoDeVenta extends Model
{
    protected $table = "cont_puntoventa";

    protected $primaryKey = "idpuntoventa";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idpuntoventa',
        'idestablecimiento',
        'idempleado',
        'codigoptoemision'
    ];
    
    public function empleado(){
    	return $this->belongsTo('App\Modelos\Empleado');
    }

   
}
