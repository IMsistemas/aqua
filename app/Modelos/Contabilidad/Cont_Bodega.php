<?php
 
namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_Bodega extends Model
{
    protected $table = "cont_bodega";

    protected $primaryKey = "idbodega";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idbodega','idparroquia','idempleado','direccionbodega', 'telefonobodega',
  		'telefonosecundariobodega','telefonoopcionalbodega','observacion','estado','idplancuenta' , 'namebodega'      
    ];
    
    public function empleado(){
    	return $this->belongsTo('App\Modelos\Empleado');
    }

   
}
