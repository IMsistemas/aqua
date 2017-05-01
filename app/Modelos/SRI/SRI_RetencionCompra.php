<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_RetencionCompra extends Model
{
    protected $table = 'sri_retencioncompra';
    protected $primaryKey = 'idretencioncompra';
    public $timestamps = false;
}
