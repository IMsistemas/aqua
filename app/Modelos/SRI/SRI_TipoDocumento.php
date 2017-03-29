<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoDocumento extends Model
{
    protected $table = 'sri_tipodocumento';
    protected $primaryKey = 'idtipodocumento';
    public $timestamps = false;
}
