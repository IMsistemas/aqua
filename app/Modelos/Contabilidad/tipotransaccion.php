<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class tipotransaccion extends Model
{
    protected $table = "cont_tipotransaccion";

    protected $primaryKey = "idtipotransaccion";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idtipotransaccion',
        'idtipoingresoegreso',
        'descripcion',
        'estado'
    ];
    public function ingresoegreso()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\tipoingresoegreso',"idtipoingresoegreso");
    }
}
