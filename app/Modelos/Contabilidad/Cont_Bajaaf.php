<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_bajaaf extends Model
{
    protected $table = 'cont_bajaaf';
    protected $primaryKey = 'idbajaaf';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iddetalleitemactivofijo','idconceptobajaaf','fecha','descripcion'       
    ];

  
}
