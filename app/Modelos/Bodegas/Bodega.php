<?php
 
namespace App\Modelos\Bodegas;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $table = "bodega";

    protected $primaryKey = "idbodega";

    public $incrementing = false;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'idbodega','idsector','idempleado','direccionbodega', 'telefonobodega',
  		'telefonosecundariobodega','telefonoopcionalbodega','observacion','estado','fechaingreso'        
    ];
    
    public function empleado(){
    	return $this->belongsTo('App\Modelos\Empleado');
    }

   
}
