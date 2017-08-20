<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoTransaccionATS extends Model
{
    protected $table = 'sri_tipotransaccionats';
    protected $primaryKey = 'idtipotransaccionats';
    public $timestamps = false;
}
