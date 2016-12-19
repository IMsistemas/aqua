<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class CompraProducto extends Model
{
    protected $table = "documentocompra";

    protected $primaryKey = "codigocompra";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigocompra', 'codigocomprbante', 'codigosustento', 'idproveedor', 'numeroretencion', 'codigotipopago', 
    		'codigopais', 'idformapago', 'codigoformapago', 'numerodocumentoproveedor', 'fechaemisionfacturaproveedor', 
    		'fecharegistrocompra', 'autorizacionfacturaproveedor', 'subtotalivacompra', 'subtotalnoivacompra', 
    		'descuentocompra', 'ivacompra', 'totalcompra', 'otrosvalores', 'procentajedescuentocompra', 
    		'estapagada', 'estaanulada', 'fechapago' ,'fechacaducidad'       
    ];
    
    

   
}
