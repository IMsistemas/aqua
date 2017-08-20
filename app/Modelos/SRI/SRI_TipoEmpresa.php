<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoEmpresa extends Model
{
    protected $table = 'sri_tipoempresa';
    protected $primaryKey = 'idtipoempresa';
    public $timestamps = false;
}
