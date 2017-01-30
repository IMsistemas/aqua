<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class Cont_ConfiguracionContable extends Model
{
    protected $table = "cont_configuracioncontabilidad";

    protected $primaryKey = "idconfiguracioncontabilidad";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idconfiguracioncontabilidad','optionname','optionvalue'   
    ];
    
    

   
}
