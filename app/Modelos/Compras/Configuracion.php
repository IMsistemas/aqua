<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $table = "configuracion";

    protected $primaryKey = "idconfiguracion";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idconfiguracion','fechaingreso','iva','ice'        
    ];
    
    

   
}
