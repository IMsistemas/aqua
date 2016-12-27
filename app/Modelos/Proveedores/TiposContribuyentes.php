<?php
 
namespace App\Modelos\Proveedores;

use Illuminate\Database\Eloquent\Model;

class TiposContribuyentes extends Model
{
    protected $table = "tipoidentificacion";

    protected $primaryKey = "codigotipoid";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'tipoidentificacion',
        'tipotransaccion'
    ];

   public function proveedores()
    {
        return $this->hasMany('App\Modelos\Proveedores\Proveedores','idproveedor');
    }
}