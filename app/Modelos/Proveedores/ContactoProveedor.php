<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class ContactoProveedor extends Model
{
    protected $table = "contactosprovedor";

    protected $primaryKey = "idcontacto";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idproveedor', 
        'nombrecontacto',
        'telefonoprincipal',
        'telefonosecundario',
        'celular',
        'observacion',
    ];

    public function proveedor()
    {
        return $this->belongsTo('App\Modelos\Proveedores\Proveedor', 'idproveedor');
    }
   
}