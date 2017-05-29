<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_tipomantencionaf extends Model
{
    protected $table = 'cont_tipomantencionaf';
    protected $primaryKey = 'idtipomantencionaf';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'tipo'   
    ];

  
}
