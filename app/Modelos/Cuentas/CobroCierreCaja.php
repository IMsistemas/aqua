<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CobroCierreCaja extends Model
{
    protected $table = 'cobrocierrecaja';
    protected $primaryKey = 'idcobrocierrecaja';
    public $timestamps = false;
}
