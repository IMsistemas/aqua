<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{

    protected $table = 'proveedor';
    protected $primaryKey = 'idproveedor';
    public $timestamps = false;

}
