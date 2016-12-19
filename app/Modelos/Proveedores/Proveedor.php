<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = "proveedor";

    protected $primaryKey = "idproveedor";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idtipoproveedor', 
        'idsector',
        'codigotipoid',
        'documentoproveedor',
        'razonsocialproveedor',
        'telefonoproveedor',
        'direccionproveedor',
        'correocontactoproveedor',
        'estado'
    ];

    public function contactosproveedores()
    {
        return $this->hasMany('App\Modelos\Proveedores\ContactoProveedor', 'idcontacto');
    }
    public function tipocontribuyente()
    {
        return $this->belongsTo('App\Modelos\Proveedores\TiposContribuyentes','codigotipoid');
    }
    public function sector()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Sectores','idsector');
    }
    public function cuentasproveedores()
    {
        return $this->belongsTo('App\Modelos\Proveedores\CuentasProveedores','numerocuenta3');
    }

   
}
