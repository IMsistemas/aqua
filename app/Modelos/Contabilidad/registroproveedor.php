<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class registroproveedor extends Model
{
    protected $table = "cont_registroproveedor";

    protected $primaryKey = "idregistroproveedor";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idregistroproveedor',
        'idtransaccion',
        'idproveedor',
        'fecha',
        'debe',
        'haber',
        'numerodocumento'
    ];

    public function proveedor()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Proveedor',"idproveedor");
    }
    public function transaccion()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\transaccion',"idtransaccion");
    }
}
