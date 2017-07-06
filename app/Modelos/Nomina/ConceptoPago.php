<?php

namespace App\Modelos\Nomina;

use Illuminate\Database\Eloquent\Model;

class ConceptoPago extends Model
{
    protected $table = 'rrhh_conceptospago';
    protected $primaryKey = 'id_conceptospago';
    public $timestamps = false;
}
