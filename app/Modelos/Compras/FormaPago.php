<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = "formapagocompra";

    protected $primaryKey = "codigoformapago3";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigoformapago3','nombreformapago'        
    ];
    
    

   
}
