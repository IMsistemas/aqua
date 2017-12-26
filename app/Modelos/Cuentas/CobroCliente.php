<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CobroCliente extends Model
{
    protected $table = 'cobrocliente';
    protected $primaryKey = 'idcobrocliente';
    public $timestamps = false;
}
