<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_ClaseItem extends Model
{
    protected $table = 'cont_claseitem';

    protected $primaryKey = 'idclaseitem';

    protected $dateFormat = 'Y-m-d H:i:s.u';


}
