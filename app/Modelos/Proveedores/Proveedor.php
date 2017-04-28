<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{

    protected $table = 'proveedor';
    protected $primaryKey = 'idproveedor';
    public $timestamps = false;

    public function persona()
    {
        return $this->belongsTo('App\Modelos\Persona','idpersona');
    }

    public function sri_tipoimpuestoiva()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoImpuestoIva','idtipoimpuestoiva');
    }

    public function cont_plancuenta()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_PlanCuenta','idplancuenta');
    }

}
