<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_mantencionaf extends Model
{
    protected $table = 'cont_mantencionaf';
    protected $primaryKey = 'idmantencionaf';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iddetalleitemactivofijo','idtipomantencionaf','fecha','observacion'    
    ];

  
}
