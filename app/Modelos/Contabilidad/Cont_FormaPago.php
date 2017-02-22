<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_FormaPago extends Model
{

    protected $table = 'cont_formapago';
    protected $primaryKey = 'idformapago';
    public $timestamps = false;

}
