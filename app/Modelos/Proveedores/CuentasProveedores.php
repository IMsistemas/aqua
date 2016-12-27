<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class CuentasProveedores extends Model
{
    protected $table = "cuentaproveedor";

    protected $primaryKey = "numerocuenta3";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idproveedor',
        'fechacreacioncuenta',
        'saldocuenta'
    ];

    public function proveedores()
    {
        return $this->hasOne('App\Modelos\Proveedores\Proveedor', 'idproveedor');
    }

   
}
