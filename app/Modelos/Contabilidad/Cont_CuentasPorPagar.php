<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_CuentasPorPagar extends Model
{
    protected $table = "cont_cuentasporpagar";
    protected $primaryKey = "idcuentasporpagar";
    public $timestamps = false;
}
