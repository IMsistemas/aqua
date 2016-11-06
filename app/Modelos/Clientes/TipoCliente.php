<?php

namespace App\Modelos\Clientes;

use Illuminate\Database\Eloquent\Model;

class TipoCliente extends Model
{
    protected $table = "tipocliente";
    protected $primaryKey = "id";
    public $timestamps = false;

    public function cliente(){
        return $this->hasMany('App\Modelos\Clientes\Cliente','id');
    }


    public function serviciojunta(){
        return $this->belongsToMany('App\Modelos\Servicios\ServicioJunta');
    }
}
