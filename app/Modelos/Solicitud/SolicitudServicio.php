<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolicitudServicio extends Model
{
    protected $table = 'solicitudservicio';
    protected $primaryKey = 'idsolicitudservicio';
    public $timestamps = false;
}
