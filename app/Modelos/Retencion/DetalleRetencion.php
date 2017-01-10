<?php

namespace App\Modelos\Retencion;

use Illuminate\Database\Eloquent\Model;

class DetalleRetencion extends Model
{
    protected $table = "detalleretencion";
    protected $primaryKey = "iddetalleretencion";
    public $timestamps = false;
}
