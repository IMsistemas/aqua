<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class registroempleado extends Model
{
    protected $table = "cont_registroempleado";

    protected $primaryKey = "idregistroempleado";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idregistroempleado',
        'idempleado',
        'idtransaccion',
        'fecha',
        'debe',
        'haber',
        'numerodocumento'
    ];
    public function transaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\transaccion',"idtransaccion");
    }
}
