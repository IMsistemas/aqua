<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_RegistroContable extends Model
{
    protected $table = "cont_registrocontable";

    protected $primaryKey = "idregistrocontable";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idregistrocontable',
        'idtransaccion',
        'idplancuenta',
        'fecha',
        'descripcion',
        'debe',
        'haber',
        'debe_c',
        'haber_c',
        'estadoanulado'
    ];
	public function cont_transaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_Transaccion',"idtransaccion");
    }
    public function cont_plancuentas()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_PlanCuenta',"idplancuenta");
    }
}
