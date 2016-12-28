<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class establecimiento extends Model
{
    protected $table = "establecimiento";

    protected $primaryKey = "idestablecimiento";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idestablecimiento', 
        'direccionestablecimiento'
    ];
}
