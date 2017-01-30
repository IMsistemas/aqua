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
        'iddocumentocompra', 
    		'idproveedor','idtipocomprobante','idsustentotributario','idcomprobanteretencion','idtipoimpuestoiva','numdocumentocompra',
    		'fecharegistrocompra','fechaemisioncompra','nroautorizacioncompra','subtotalconimpuestocompra',
    		'subtotalcerocompra','subtotalnoobjivacompra','subtotalexentivacompra','subtotalsinimpuestocompra',
    		'totaldescuento','icecompra numeric','ivacompra','irbpnrcompra','propinacompra','otroscompra',
    		'valortotalcompra','estaAnulada'    		    
    ];
    
    

   
}
