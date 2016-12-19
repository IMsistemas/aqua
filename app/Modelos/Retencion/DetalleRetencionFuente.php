<?php

namespace App\Modelos\Retencion;

use Illuminate\Database\Eloquent\Model;

class DetalleRetencionFuente extends Model
{
    protected $table = "detalleretencionfuente";
    protected $primaryKey = "iddetalleretencionfuente";
    public $timestamps = false;
}
