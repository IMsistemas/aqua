<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_DocumentoVenta extends Model
{

    protected $table = 'cont_documentoventa';
    protected $primaryKey = 'iddocumentoventa';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iddocumentoventa', 
        'idpuntoventa',
        'idcliente',
        'idtipoimpuestoiva',
        'numdocumentoventa',
        'fecharegistroventa',
        'fechaemisionventa',
        'nroautorizacionventa',
        'nroguiaremision',
        'subtotalconimpuestoventa',
        'subtotalceroventa',
        'subtotalnoobjivaventa',
        'subtotalexentivaventa',
        'subtotalsinimpuestoventa', 
        'totaldescuento',
        'icecompra',
        'ivacompra',
        'irbpnrventa',
        'propina',
        'otrosventa',
        'valortotalventa',
        'estadoanulado'
    ];

    public function cont_documentoguiaremision(){
    	return $this->hasMany('App\Modelos\Contabilidad');
    }
    public function cont_itemventa(){
        return $this->hasMany('App\Modelos\Contabilidad');
    }

}
