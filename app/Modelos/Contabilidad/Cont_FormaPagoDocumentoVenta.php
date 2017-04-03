<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_FormaPagoDocumentoVenta extends Model
{

    protected $table = 'cont_formapago_documentoventa';
    public $timestamps = false;
    protected $primaryKey = null;

    /*protected $fillable = [
        'idformapago',
        'iddocumentoventa'
    ];*/

}
