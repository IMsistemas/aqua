<?php
 
namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_DocumentoCompra extends Model
{
    protected $table = "cont_documentocompra";

    protected $primaryKey = "iddocumentocompra";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'iddocumentocompra', 'idtransaccion',
    		'idproveedor','idtipocomprobante','idsustentotributario','idcomprobanteretencion','idtipoimpuestoiva','numdocumentocompra',
    		'fecharegistrocompra','fechaemisioncompra','nroautorizacioncompra','subtotalconimpuestocompra',
    		'subtotalcerocompra','subtotalnoobjivacompra','subtotalexentivacompra','subtotalsinimpuestocompra',
    		'totaldescuento','icecompra','ivacompra','irbpnrcompra','propinacompra','otroscompra',
    		'valortotalcompra','estadoanulado'
    ];


    public function sri_comprobanteretencion(){
        return $this->belongsTo('App\Modelos\SRI\SRI_ComprobanteRetencion',"idcomprobanteretencion");
    }
   
}
