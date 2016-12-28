<?php

namespace App\Modelos\Facturacionventa;

use Illuminate\Database\Eloquent\Model;

class catalogoservicio extends Model
{
    protected $table = "catalogoservicio";

    protected $primaryKey = "idservicio";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idservicio',
        'nombreservicio'
    ];
}
