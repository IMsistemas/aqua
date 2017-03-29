<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_TipoIngresoEgreso extends Model
{
    protected $table = "cont_tipoingresoegreso";

    protected $primaryKey = "idtipoingresoegreso";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idtipoingresoegreso',
        'descripcion'
    ];

}
