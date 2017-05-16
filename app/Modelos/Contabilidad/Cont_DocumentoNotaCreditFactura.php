<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_DocumentoNotaCreditFactura extends Model
{
    protected $table = 'cont_documentonotacreditfactura';
    protected $primaryKey = 'iddocumentonotacreditfactura';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'iddocumentonotacreditfactura',
        //'idpuntoventa',
        'idcliente',
        'idtipoimpuestoiva',
        'numdocumentonotacredit',
        'fecharegistroncf',
        'fechaemisionncf',
        'nroautorizacionncf',
        //'nroguiaremision',
        'subtotalconimpuestoncf',
        'subtotalceroncf',
        'subtotalnoobjivancf',
        'subtotalexentivancf',
        'subtotalsinimpuestoncf',
        'totaldescuento',
        'icencf',
        'ivancf',
        'irbpnrncf',
        'propinancf',
        'otrosncf',
        'valortotalncf',
        'estadoanulado',
        'idtransaccion',
        'motivoncf'
    ];

    public function cliente(){
        return $this->belongsTo('App\Modelos\Clientes\Cliente',"idcliente");
    }


    public function cont_itemnotacreditfactura(){
        return $this->hasMany('App\Modelos\Contabilidad\Cont_ItemNotaCreditFactura',"iddocumentonotacreditfactura");
    }

    /*public function cont_puntoventa(){
        return $this->belongsTo('App\Modelos\Contabilidad\Cont_PuntoDeVenta',"idpuntoventa");
    }*/

    /*public function cont_formapago_documentoventa(){
        return $this->hasMany('App\Modelos\Contabilidad\Cont_FormaPagoDocumentoVenta',"iddocumentoventa");
    }*/
}
