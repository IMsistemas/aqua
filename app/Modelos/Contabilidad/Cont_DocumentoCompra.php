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


    public function sri_comprobanteretencion()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_ComprobanteRetencion',"idcomprobanteretencion");
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Proveedor',"idproveedor");
    }

    public function sri_sustentotributario()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_SustentoTributario',"idsustentotributario");
    }

    public function sri_tipocomprobante()
    {
        return $this->belongsTo('App\Modelos\SRI\SRI_TipoComprobante',"idtipocomprobante");
    }

    public function cont_formapago_documentocompra()
    {
        return $this->hasMany('App\Modelos\Contabilidad\Cont_FormaPagoDocumentoCompra', 'iddocumentocompra');
    }
   
}
