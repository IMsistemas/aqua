<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class tipoingresoegreso extends Model
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
