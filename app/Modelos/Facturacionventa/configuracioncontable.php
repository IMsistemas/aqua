<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class configuracioncontable extends Model
{
    protected $table = "configuracioncontable";

    protected $primaryKey = "idconfiguracion2";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idconfiguracion2', 
        'fechaingreso',
        'iva',
        'ice'
    ];
}
