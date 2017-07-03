<?php

namespace App\Modelos\Contabilidad;

use Illuminate\Database\Eloquent\Model;

class Cont_Conceptobajaaf extends Model
{
    protected $table = 'cont_conceptobajaaf';
    protected $primaryKey = 'idconceptobajaaf';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'concepto'       
    ];

  
}
