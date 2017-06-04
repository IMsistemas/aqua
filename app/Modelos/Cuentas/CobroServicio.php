<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CobroServicio extends Model
{
    protected $table = 'cobroservicio';
    protected $primaryKey = 'idcobroservicio';
    public $timestamps = false;

    public function cont_cuentasporcobrar()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasporCobrar',"idcobroservicio");
    }

}
