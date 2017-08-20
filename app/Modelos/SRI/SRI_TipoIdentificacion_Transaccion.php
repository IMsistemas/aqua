<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_TipoIdentificacion_Transaccion extends Model
{
    protected $table = 'sri_tipoidentificacion_transaccion';
    protected $primaryKey = false;
    public $incrementing = false;
    public $timestamps = false;

    public function sri_tipoidentificacion()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoIdentificacion','idtipoidentificacion');
    }

    public function sri_tipotransaccionats()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoTransaccionATS','idtipotransaccionats');
    }
}
