<?php

namespace App\Modelos\Retencion;

use Illuminate\Database\Eloquent\Model;

class TipoRetencion extends Model
{
    protected $table = "tiporetencion";
    protected $primaryKey = "idtiporetencion";
    public $timestamps = false;
}
