<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_TipoTransaccion extends Model
{
    protected $table = "cont_tipotransaccion";

    protected $primaryKey = "idtipotransaccion";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idtipotransaccion',
        'idtipoingresoegreso',
        'descripcion',
        'estado',
        'sigla'
    ];
    public function cont_tipoingresoegreso()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_TipoIngresoEgreso',"idtipoingresoegreso");
    }
}
