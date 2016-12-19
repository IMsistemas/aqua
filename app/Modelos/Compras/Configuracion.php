<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = "configuracioncontable";

    protected $primaryKey = "idconfiguracion2";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idconfiguracion2','fechaingreso','iva','ice'        
    ];
    
    

   
}
