<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_trasladoaf extends Model
{
     protected $table = "cont_trasladoaf";

     protected $primaryKey = "idtrasladoaf";

     protected $fillable = [ 'iddetalleitemactivofijo','fecha','idempleadoorigen','idempleadodestino'

      ];


    public $incrementing = false;

    public $timestamps = false;

}
