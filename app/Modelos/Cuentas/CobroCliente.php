<?php

namespace App\Modelos\Cuentas;

use Illuminate\Database\Eloquent\Model;

class CobroCliente extends Model
{
    protected $table = 'cobrocliente';
    protected $primaryKey = 'idcobrocliente';
    public $timestamps = false;

    public function cliente()
    {
        return $this->belongsTo('App\Modelos\Clientes\Cliente','idcliente');
    }

    public function cont_catalogitem()
    {
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_CatalogItem','idcatalogitem');
    }
}
