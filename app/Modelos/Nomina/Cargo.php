<?php

namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargo';
    protected $primaryKey = 'idcargo';
    public $timestamps = false;

    public function empleado()
    {
        return $this->hasMany('App\Modelos\Nomina\Empleado', 'idcargo');
    }
}
