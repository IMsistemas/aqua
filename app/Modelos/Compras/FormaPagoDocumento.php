<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class FormaPagoDocumento extends Model
{
    protected $table = "formapagodocumento";

    protected $primaryKey = "idformapago";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idformapago','nombreformapago'        
    ];
    
    

   
}
