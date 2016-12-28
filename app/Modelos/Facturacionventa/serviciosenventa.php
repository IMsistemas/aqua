<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class serviciosenventa extends Model
{
	protected $table = "serviciosenventa";

    //protected $primaryKey = "idproductoenventa";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigoventa',
        'idservicio'
    ];
}
