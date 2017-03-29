<?php

namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamento';
    protected $primaryKey = 'iddepartamento';
    public $timestamps = false;

    public function empleado()
    {
        return $this->hasMany('App\Modelos\Nomina\Empleado', 'iddepartamento');
    }

}
