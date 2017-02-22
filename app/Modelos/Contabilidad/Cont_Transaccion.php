<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_Transaccion extends Model
{
	protected $table = "cont_transaccion";

    protected $primaryKey = "idtransaccion";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idtransaccion',
        'idtipotransaccion',
        'numcontable',
        'fechatransaccion',
        'numcomprobante',
        'descripcion',
        'estadoanulado'
    ];
    public function cont_tipotransaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_TipoTransaccion',"idtipotransaccion");
    }
    public function cont_registrocontable()
    {
        return $this->hasMany('App\Modelos\Contabilidad\Cont_RegistroContable',"idtransaccion");
    }
}
