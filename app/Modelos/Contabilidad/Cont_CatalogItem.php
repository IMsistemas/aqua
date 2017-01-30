<?php
 
namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_CatalogItem extends Model
{
    protected $table = "cont_catalogitem";

    protected $primaryKey = "idcatalogitem";

    public $incrementing = false;

    public $timestamps = false;


   
}
