<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class puntoventa extends Model
{
	protected $table = "puntoventa";

    protected $primaryKey = "idpuntoventa";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idpuntoventa', 
        'idestablecimiento',
        'idempleado'
    ];

    public function empleado(){
    	return $this->belongsTo('App\Modelos\Nomina\Empleado','idempleado');
    }

    public function establecimiento(){
    	return $this->belongsTo('App\Modelos\Facturacionventa\establecimiento','idestablecimiento');
    }

    public function venta()
    {
        return $this->hasMany('App\Modelos\Facturacionventa\venta',"idpuntoventa");
    }
}
