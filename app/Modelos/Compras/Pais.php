<?php
 
namespace App\Modelos\Compras;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = "paispago";

    protected $primaryKey = "codigopais";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigopais','nombrepais'        
    ];
    
    

   
}
