<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_Sustento_Comprobantev1 extends Model
{
    protected $table = 'sri_sustento_comprobante';
    protected $primaryKey = 'idtipocomprobante';
    public $timestamps = false;
}
