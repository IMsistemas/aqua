<?php

namespace App\Modelos\Solicitud;

use Illuminate\Database\Eloquent\Model;

class SolicitudServicio extends Model
{
    protected $table = 'solicitudservicio';
    protected $primaryKey = 'idsolicitudservicio';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente', 'codigocliente');
    }

    public function cont_cuentasporcobrar()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasporCobrar',"idcobroservicio");
    }

    public function catalogoitem_solicitudservicio()
    {
        return $this->hasMany('App\Modelos\Cuentas\CatalogoItemSolicitudServicio', 'idsolicitudservicio');
    }

}
