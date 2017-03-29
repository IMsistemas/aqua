<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_PlanCuenta extends Model
{
    protected $table = "cont_plancuenta";

    protected $primaryKey = "idplancuenta";

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'idplancuenta',
        'concepto',
        'codigosri',
        'tipoestadofinanz',
        'estado',
        'controlhaber',
        'jerarquia',
        'tipocuenta'
    ];
}
