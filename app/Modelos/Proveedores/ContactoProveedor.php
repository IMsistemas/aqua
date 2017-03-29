<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class ContactoProveedor extends Model
{
    protected $table = 'contactoproveedor';
    protected $primaryKey = 'idcontactoproveedor';
    public $timestamps = false;

    public function proveedor()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Proveedor', 'idproveedor');
    }
   
}