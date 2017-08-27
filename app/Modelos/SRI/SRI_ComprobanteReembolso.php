<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_ComprobanteReembolso extends Model
{
    protected $table = 'sri_comprobantereembolso';
    protected $primaryKey = 'idcomprobantereembolso';
    public $timestamps = false;
}
