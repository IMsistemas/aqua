<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoEmision extends Model
{
    protected $table = 'sri_tipoemision';
    protected $primaryKey = 'idtipoemision';
    public $timestamps = false;
}
