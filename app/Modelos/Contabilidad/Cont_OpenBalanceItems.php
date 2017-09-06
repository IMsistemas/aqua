<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_OpenBalanceItems extends Model
{
    protected $table = 'cont_openbalanceitems';
    protected $primaryKey = 'idopenbalanceitems';
    public $timestamps = true;
}
