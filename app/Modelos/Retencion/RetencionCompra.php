<?php

namespace App\Modelos\Retencion;

use Illuminate\Database\Eloquent\Model;

class RetencionCompra extends Model
{
    protected $table = "retencioncompra";
    protected $primaryKey = "idretencioncompra";
    public $timestamps = false;
}
