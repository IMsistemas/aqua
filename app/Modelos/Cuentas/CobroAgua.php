<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;


class CobroAgua extends Model
{
     protected $table = 'cobroagua';
     protected $primaryKey = 'idcobroagua';
     public $timestamps = false;

    public function cont_cuentasporcobrar()
    {
        return $this->hasMany('App\Modelos\Cuentas\CuentasporCobrar',"idcobroagua");
    }

    public function lectura()
    {
        return $this->belongsTo('App\Modelos\Lecturas\Lectura','idlectura');
    }

    public function suministro()
    {
        return $this->belongsTo('App\Modelos\Suministros\Suministro','idsuministro');
    }

    public function catalogoitem_cobroagua()
    {
        return $this->hasMany('App\Modelos\Cuentas\CatalogoItemCobroAgua','idcobroagua');
    }

    public function otrosvalores_cobroagua()
    {
        return $this->hasMany('App\Modelos\Facturas\OtrosValoresCobroAgua','idcobroagua');
    }

    public function factura()
    {
        return $this->belongsTo('App\Modelos\Facturas\Factura','idfactura');
    }

}
