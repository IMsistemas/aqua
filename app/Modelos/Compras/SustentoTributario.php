<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class SustentoTributario extends Model
{
    protected $table = "sustentocomporbante";

    protected $primaryKey = "codigosustento";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigosustento','nombresustento'        
    ];
    
    

   
}
