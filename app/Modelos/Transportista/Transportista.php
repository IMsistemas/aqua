<?php

namespace App\Modelos\Transportista;

use Illuminate\Database\Eloquent\Model;

class Transportista extends Model
{
    protected $table = 'transportista';
    protected $primaryKey = 'idtransportista';
    public $timestamps = false;

    public function persona()
    {
        return $this->belongsTo('App\Modelos\Persona', 'idpersona');
    }

    public function cont_documentoguiaremision()
    {
        return $this->hasMany('App\Modelos\Contabilidad\Cont_DocumentoGuiaRemision', 'idtransportista');
    }

}