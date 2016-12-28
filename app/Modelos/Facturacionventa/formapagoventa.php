<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class formapagoventa extends Model
{
	protected $table = "formapagoventa";

    protected $primaryKey = "codigoformapago";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigoformapago', 
        'nombreformapago'
    ];
}
