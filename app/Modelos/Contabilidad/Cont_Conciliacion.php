<?php
 
namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_Conciliacion extends Model
{
    protected $table = "cont_conciliacion";

    protected $primaryKey = "idconciliacion";

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'idconciliacion',
        'descripcion',
        'fecha',
        'balanceinicial',
        'balancefinal',
        'idplancuenta',
        'estadoanulado',
        'estadoconciliacion'
    ];
    
    public function cont_plancuenta()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_PlanCuenta',"idplancuenta");
    }
   
}
