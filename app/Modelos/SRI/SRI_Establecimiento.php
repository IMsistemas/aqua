<?php

namespace App\Modelos\SRI;

use Illuminate\Database\Eloquent\Model;

class SRI_Establecimiento extends Model
{

    protected $table = 'sri_establecimiento';
    protected $primaryKey = 'idestablecimiento';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'idestablecimiento', 'ruc',' razonsocial',' nombrecomercial',' direccionestablecimiento','         contribuyenteespecial', 'obligadocontabilidad', 'rutalogo'
    ];


}
