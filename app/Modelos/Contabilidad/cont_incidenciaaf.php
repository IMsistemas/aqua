<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_incidenciaaf extends Model
{
    protected $table = 'cont_incidenciaaf';
    protected $primaryKey = 'idincidenciaaf';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iddetalleitemactivofijo','fecha','descripcion'       
    ];

  
}
