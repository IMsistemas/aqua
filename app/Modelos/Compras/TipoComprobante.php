<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class TipoComprobante extends Model
{
    protected $table = "tipocomprobante";

    protected $primaryKey = "codigocomprbante";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigocomprbante','nombretipocomprobante'        
    ];
    
    

   
}
