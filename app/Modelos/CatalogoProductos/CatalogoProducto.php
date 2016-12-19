<?php
 
namespace App\Modelos\CatalogoProductos;

use Illuminate\Database\Eloquent\Model;

class CatalogoProducto extends Model
{
    protected $table = "catalogoproducto";

    protected $primaryKey = "codigoproducto";

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'codigoproducto', 'idkardex', 'idcategoria', 'nombreproducto',
        'fechaingreso', 'rutafoto'        
    ];

   
}
