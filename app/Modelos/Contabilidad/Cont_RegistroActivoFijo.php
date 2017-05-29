<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_registroactivofijo extends Model
{
    protected $table = 'cont_registroactivofijo';
    protected $primaryKey = 'idregistroactivofiijo';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iddetalleitemactivofijo','idtransaccion','fecha','debe','haber','numerodocumento'              
    ];

  
}
