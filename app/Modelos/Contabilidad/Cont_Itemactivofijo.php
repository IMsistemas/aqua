<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_Itemactivofijo extends Model
{
     protected $table = "cont_itemactivofijo";

     protected $primaryKey = "iditemactivofijo";

     protected $fillable = [ 'idcatalogitem' ];


    public $incrementing = false;

    public $timestamps = false;

}
