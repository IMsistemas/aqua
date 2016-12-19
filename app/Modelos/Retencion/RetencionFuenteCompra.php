<?php

namespace App\Modelos\Retencion;

use Illuminate\Database\Eloquent\Model;

class RetencionFuenteCompra extends Model
{
    protected $table = "retencionfuentecompra";
    protected $primaryKey = "idretencionfuente";
    public $timestamps = false;
}
