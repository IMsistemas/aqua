<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class registrocontable extends Model
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
        'haber'
    ];
	public function transaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\transaccion',"idtransaccion");
    }
    public function plancuentas()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\plancuenta',"idplancuenta");
    }
}
