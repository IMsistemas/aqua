<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class transaccion extends Model
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
        'descripcion'
    ];
    public function tipotransaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\tipotransaccion',"idtipotransaccion");
    }
}
