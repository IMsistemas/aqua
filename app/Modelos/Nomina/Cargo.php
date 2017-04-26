<?php

namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargo';
    protected $primaryKey = 'idcargo';
    public $timestamps = false;

    public function departamento()
    {
        return $this->belongsTo('App\Modelos\Nomina\Departamento', 'iddepartamento');
    }

    public function empleado()
    {
        return $this->hasMany('App\Modelos\Nomina\Empleado', 'idcargo');
    }
}
