<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolSuministro extends Model
{
    protected $table = 'solsuministro';
    protected $primaryKey = 'idsolicitudsuministro';
    public $timestamps = false;
}
